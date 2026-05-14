# Document Nest Next Roadmap Plan

This plan covers the next product slice:

- Email reminders for documents expiring soon
- Saved filter views for common workflows
- Bulk document actions
- Document activity history
- Improved mobile document preview experience

The implementation should keep the current app shape: Laravel controllers with Form Request authorization, service and
repository classes for domain behavior, policies for user ownership, Inertia Vue pages under `resources/js/Pages`, and
focused PHPUnit coverage for every change.

For this slice, document activity history will use MongoDB as a dedicated append-oriented activity store. PostgreSQL
remains the source of truth for users, documents, categories, tags, reminders, saved filters, and authorization-critical
data.

## Goals

1. Help users act before important documents expire.
2. Reduce repeated filtering work for common document lists.
3. Make repeated document maintenance efficient through bulk actions.
4. Show a useful audit trail for user-visible document changes.
5. Make document preview usable on mobile screens without breaking private storage.

## Non-Goals

- No multi-user sharing or household collaboration in this slice.
- No external cloud storage migration; keep private local storage behind authorized routes.
- No SMS, push, or calendar integrations yet.
- No full-text search engine dependency yet; continue using the current database-backed search.
- No migration of core application data to MongoDB. MongoDB is only for document activity history in this slice.

## MongoDB Activity Store Setup

This slice introduces a new infrastructure dependency. Before building activity history, add and verify MongoDB support
locally, in tests, and in deployment.

### Package and Configuration

- Add the official MongoDB Laravel integration package: `mongodb/laravel-mongodb`.
- Add a `mongodb` connection to `config/database.php`, using environment variables:
    - `MONGODB_DSN`
    - `MONGODB_DATABASE`
- Keep `pgsql` as the default Laravel database connection.
- Set the activity model connection explicitly so only activity records use MongoDB.
- Add MongoDB to `docker-compose.yml` for local development.
- Add deployment notes for provisioning MongoDB in the target environment before this feature ships.

### Runtime Boundary

- PostgreSQL remains authoritative for document existence and ownership.
- MongoDB activity records should duplicate only the identifiers and display data needed for history:
    - `document_id`
    - `user_id`
    - `actor_id`
    - `document_title`
    - `type`
    - `description`
    - `metadata`
    - `created_at`
- Do not rely on MongoDB records for authorization decisions. Always authorize against PostgreSQL `documents`.
- Activity writes are best-effort but should be observable. If MongoDB is unavailable, log/report the failure from the
  queued listener without rolling back the already-committed core document action unless the action specifically
  requires the activity row.
- Document changes should dispatch Laravel events after the PostgreSQL transaction commits. Queued listeners will create
  MongoDB activity records asynchronously.

### MongoDB Collection and Indexes

- Use a `document_activities` MongoDB collection.
- Add indexes for the expected access patterns:
    - `{ document_id: 1, created_at: -1 }`
    - `{ user_id: 1, created_at: -1 }`
    - `{ type: 1, created_at: -1 }`
    - optional `{ actor_id: 1, created_at: -1 }`
- Do not add a TTL index for user-visible activity history unless a retention policy is explicitly chosen later.

### Laravel Events and Queue

- Use Laravel events as the boundary between core document workflows and MongoDB activity writes.
- Configure queued event handling so listeners run only after PostgreSQL commits:
    - Prefer event classes implementing `ShouldDispatchAfterCommit`.
    - Use queued listeners implementing `ShouldQueueAfterCommit`.
    - If the queue connection has `after_commit` enabled globally, still keep the event/listener contracts explicit for
      readability.
- Create event classes under `app/Events/Documents`:
    - `DocumentCreated`
    - `DocumentUpdated`
    - `DocumentArchived`
    - `DocumentRestored`
    - `DocumentDeleted`
    - `DocumentDownloaded`
    - `DocumentReminderSent`
    - `DocumentsBulkActionCompleted`, or individual per-document bulk events if per-document listeners are simpler.
- Events should carry a stable activity payload, not live mutable model state:
    - `document_id`
    - `user_id`
    - `actor_id`
    - `document_title`
    - `type`
    - `description`
    - sanitized `metadata.original`
    - sanitized `metadata.updated`
    - `metadata.changed_fields`
    - optional operation context such as `bulk_action_id` or `source`
- Create `CreateDocumentActivity` queued listener. Its only responsibility is to persist the event payload to MongoDB
  through `DocumentActivityService`.
- Set listener retry behavior deliberately:
    - small retry count
    - exponential backoff
    - failure reporting with document id, user id, and event type
- Keep activity listeners idempotent where practical. Include an optional `activity_id` UUID in the event payload and
  use it as a unique key in MongoDB to prevent duplicate activity rows after queue retries.

## Phase 1: Document Activity History

Build this first because reminders and bulk actions should write to the same history system.

### Data Model

- Add `DocumentActivity` MongoDB model backed by the `document_activities` collection.
- Use string UUID values from PostgreSQL for `document_id`, `user_id`, and `actor_id`.
- Store denormalized display fields so historical entries remain understandable if the document title changes later.
- Suggested MongoDB document shape:

```json
{
  "_id": "mongo-object-id",
  "document_id": "uuid",
  "user_id": "uuid",
  "actor_id": "uuid",
  "document_title": "Passport scan",
  "type": "updated",
  "description": "Updated document details",
  "metadata": {
    "original": {
      "title": "Passport scan",
      "status": "active",
      "category_id": "uuid",
      "tag_ids": ["uuid"],
      "notes": "Stored for travel renewals.",
      "issue_date": "2021-08-12",
      "expiry_date": "2026-08-12"
    },
    "updated": {
      "title": "Passport scan",
      "status": "archived",
      "category_id": "uuid",
      "tag_ids": ["uuid"],
      "notes": "Stored for travel renewals.",
      "issue_date": "2021-08-12",
      "expiry_date": "2026-08-12"
    },
    "changed_fields": ["status"]
  },
  "created_at": "2026-05-14T00:00:00Z",
  "updated_at": "2026-05-14T00:00:00Z"
}
```

- Add casts for `metadata`, `created_at`, and `updated_at`.
- For update-style events, store full sanitized `original` and `updated` snapshots in metadata, plus `changed_fields`
  for quick UI display.
- For create events, use `original: null` and `updated: { ... }`.
- For delete events, use `original: { ... }` and `updated: null`.
- Add `DocumentActivityType` enum for stable event names:
    - `Created`
    - `Updated`
    - `Archived`
    - `Restored`
    - `Deleted`
    - `Downloaded`
    - `Previewed`
    - `ReminderSent`
    - `BulkArchived`
    - `BulkRestored`
    - `BulkDeleted`

### Backend

- Create `DocumentActivityPayloadFactory` to build sanitized original/updated snapshots and event payloads from document
  state.
- Create `DocumentActivityService` with a method that accepts the immutable event payload and writes the MongoDB record.
- Keep the queued listener as the only place that calls `DocumentActivityService` for writes.
- Wrap MongoDB write failures with reporting/logging so activity failures are visible.
- Dispatch activity events from existing document service methods:
    - create
    - update
    - archive
    - restore
    - delete
- Capture the `original` snapshot before mutating the document and the `updated` snapshot after the mutation succeeds.
- Dispatch events after commit so listeners do not read or record rolled-back PostgreSQL state.
- Decide whether preview/download events should dispatch activity events immediately. Recommendation: dispatch download
  events, skip preview events by default to avoid noisy histories.
- Add `DocumentActivityRepository` for paginating MongoDB activity records for a user-owned document.
- Add route `GET /documents/{document}/activity`, or include recent activity as a deferred prop on `documents.show`.
- Keep authorization through `DocumentPolicy::view`.

### Frontend

- Add an Activity section on `resources/js/Pages/Documents/Show.vue`.
- Show timestamp, event label, description, and the actor.
- Use a compact timeline that works in the existing details layout.
- If using deferred props, include a skeleton or loading state.

### Tests

- Feature test that create/update/archive/restore/delete dispatch the expected Laravel events.
- Listener test that a document activity event writes the expected MongoDB activity row.
- Queue test that `CreateDocumentActivity` is queued after commit for transactional document changes.
- Feature test that users cannot see another user's document activity, even if MongoDB contains matching records.
- Unit test for `DocumentActivityService` description/metadata behavior.
- Unit test for `DocumentActivityPayloadFactory` original/updated snapshots and changed fields.
- Test MongoDB write failure handling does not break core document updates unless we deliberately make activity
  required.
- Existing document management tests should continue passing.

## Phase 2: Email Reminders for Expiring Documents

Implement after activity history so reminder sends are traceable.

### Data Model

- Add `document_reminders` table:
    - `id` UUID primary key
    - `document_id` UUID foreign key, cascade delete
    - `user_id` UUID foreign key, cascade delete
    - `remind_on` date, indexed
    - `sent_at` timestamp nullable
    - `created_at` / `updated_at`
    - unique index on `document_id`, `remind_on`
- Add optional user-level reminder settings later. For this slice, use a default policy:
    - 30 days before expiry
    - 7 days before expiry
    - 1 day before expiry

### Backend

- Create `DocumentReminder` model and factory.
- Create `DocumentReminderService`:
    - sync reminder dates when a document is created or its expiry date changes
    - remove unsent reminders when expiry date is cleared
    - ignore archived or deleted documents
- Create queued notification or queued mailable for reminder email.
    - Prefer Laravel notifications if the message is user/action oriented.
    - Queue mail so request work remains fast.
- Add scheduled command:
    - `documents:send-expiry-reminders`
    - finds unsent reminders due today or earlier
    - sends email
    - sets `sent_at`
    - dispatches `DocumentReminderSent` after commit so the queued activity listener records `ReminderSent` in MongoDB
- Register command in scheduler with `daily()`, `withoutOverlapping()`, and a reasonable timezone decision.
- Add dashboard links from reminder email to the document show page.

### Frontend

- Show reminder status on document detail:
    - upcoming reminder dates
    - last sent reminder, if any
- Add an expiring-soon dashboard callout only if it provides new information beyond the current section.

### Tests

- Unit tests for reminder date generation.
- Feature tests for syncing reminders on create/update/cleared expiry/archive.
- Command test with `Mail::fake()` or `Notification::fake()` asserting queued outgoing mail.
- Test that reminders are not sent for another user's data, archived documents, or already-sent reminder rows.
- Test `DocumentReminderSent` is dispatched after a reminder is sent.
- Listener test that the reminder event writes the expected MongoDB activity row.

## Phase 3: Saved Filter Views

Build on the existing `DocumentFiltersData` and documents index query string behavior.

### Data Model

- Add `saved_document_filters` table:
    - `id` UUID primary key
    - `user_id` UUID foreign key, cascade delete
    - `name` string
    - `filters` JSON
    - `sort` string nullable
    - `direction` string nullable
    - `is_default` boolean default false
    - timestamps
    - unique index on `user_id`, `name`
- Add `SavedDocumentFilter` model with JSON cast and `ownedBy` scope.

### Backend

- Create `SavedDocumentFilterPolicy`.
- Create Form Requests:
    - `StoreSavedDocumentFilterRequest`
    - `UpdateSavedDocumentFilterRequest`
    - `DestroySavedDocumentFilterRequest`
    - `ApplySavedDocumentFilterRequest`, if needed
- Create controllers under `app/Http/Controllers/SavedDocumentFilters`.
- Add routes under auth:
    - `POST /document-filters`
    - `PATCH /document-filters/{savedDocumentFilter}`
    - `DELETE /document-filters/{savedDocumentFilter}`
- Reuse `IndexDocumentRequest` validation rules where practical so saved filters cannot store invalid query state.
- Add service method to normalize filters before persisting.
- Add repository method to list filters for the current user, ordered by default first and then name.

### Frontend

- Extend `resources/js/Pages/Documents/Index.vue`:
    - saved views dropdown
    - "Save current view" action
    - rename and delete actions
    - optional "Make default" action
- Applying a saved view should update the URL query and reload documents.
- Keep active filter state visible so saved views do not become hidden state.
- Avoid modal complexity unless inline controls become cramped.

### Tests

- Feature tests for create/update/delete/apply saved filters.
- Authorization tests for cross-user access.
- Validation tests for invalid filter payloads.
- Inertia test that documents index receives saved filters.
- Browser test for the core workflow: filter documents, save view, apply view.

## Phase 4: Bulk Document Actions

Implement after saved filters so users can filter down to a working set and act on selected rows.

### Backend

- Add bulk routes:
    - `POST /documents/bulk/archive`
    - `POST /documents/bulk/restore`
    - `POST /documents/bulk/delete`
    - Optional: `POST /documents/bulk/category`
    - Optional: `POST /documents/bulk/tags`
- Create Form Requests that validate:
    - `document_ids` required array
    - each id is a UUID
    - each document belongs to the authenticated user
    - target category/tag ids belong to the authenticated user
- Add `DocumentBulkActionService`:
    - loads user-owned documents by ids
    - applies the action in a transaction
    - deletes files for bulk delete
    - dispatches one activity event per changed document, or one bulk event that the listener expands into one MongoDB
      activity row per document
    - returns counts for flash messages
- Keep delete behavior consistent with current hard delete behavior.
- Do not use global mass update if per-document activity and file deletion are required.

### Frontend

- Extend documents index with selection state:
    - row checkboxes
    - select all visible rows
    - selected count
    - compact bulk action toolbar
    - confirmation for destructive delete
- Keep interactions stable with current pagination. "Select all" should mean current page only for this slice.
- Disable bulk toolbar while request is processing.
- Clear selection after successful action.

### Tests

- Feature tests for archive/restore/delete selected documents.
- Test bulk delete removes local files.
- Test cross-user document ids are rejected or ignored consistently.
- Test bulk actions dispatch activity events for each changed document.
- Listener test that bulk activity events create MongoDB activity rows for each changed document.
- Inertia/browser smoke test for selecting visible rows and archiving them.

## Phase 5: Improved Mobile Document Preview

This is intentionally last because it should not disturb storage or authorization behavior.

### Backend

- Keep existing `documents.preview` route as the private authorized source.
- Add response headers only if needed for mobile display:
    - preserve correct `Content-Type`
    - allow inline preview
    - keep download route attachment behavior separate
- Consider a preview capability prop:
    - PDF and image mime types are previewable
    - other mime types show a download-first state

### Frontend

- Rework `resources/js/Pages/Documents/Show.vue` preview area:
    - On desktop, keep a large inline preview.
    - On mobile, use a dedicated preview action that opens a full-screen preview panel.
    - Provide explicit close, download, and edit actions.
    - For images, use responsive image containment.
    - For PDFs, use iframe/object fallback with a clear download option.
- Ensure the details and actions remain reachable without excessive scrolling.
- Avoid loading heavy preview UI on the index page.

### Tests

- Feature tests for preview authorization remain in place.
- Add browser test for mobile document preview open/close and download link visibility.
- Check browser logs after Dusk run.

## Cross-Cutting Requirements

### Authorization and Privacy

- Every new route must be inside `auth` middleware.
- Every document-scoped action must verify ownership through policy or Form Request validation.
- Saved filters, reminders, and activities must be user-scoped.
- Do not expose local storage paths to the frontend.
- MongoDB activity reads must be preceded by PostgreSQL document authorization.
- MongoDB documents must not store file paths, provider tokens, or other secrets.

### Performance

- Add indexes for:
    - `document_reminders.remind_on`
    - `document_reminders.sent_at`
    - `saved_document_filters.user_id`
- Add MongoDB collection indexes for activity lookups:
    - `{ document_id: 1, created_at: -1 }`
    - `{ user_id: 1, created_at: -1 }`
    - `{ type: 1, created_at: -1 }`
- Eager load relationships used by list resources.
- Paginate activity history; do not load unbounded timelines.
- Bulk actions should process only the selected ids for this slice.

### Activity Metadata

Use MongoDB nested metadata consistently. For document mutations, store a full sanitized snapshot of the document before
and after the action, not only changed fields. This keeps the activity record self-contained even if the PostgreSQL
document changes later.

Mutation activity shape:

```json
{
  "original": {
    "title": "Passport scan",
    "status": "active",
    "category_id": "uuid",
    "tag_ids": ["uuid"],
    "notes": "Stored for travel renewals.",
    "issue_date": "2021-08-12",
    "expiry_date": "2026-08-12"
  },
  "updated": {
    "title": "Passport scan",
    "status": "archived",
    "category_id": "uuid",
    "tag_ids": ["uuid"],
    "notes": "Stored for travel renewals.",
    "issue_date": "2021-08-12",
    "expiry_date": "2026-08-12"
  },
  "changed_fields": ["status"]
}
```

Create activity shape:

```json
{
  "original": null,
  "updated": {
    "title": "Passport scan",
    "status": "active",
    "category_id": "uuid",
    "tag_ids": ["uuid"],
    "notes": "Stored for travel renewals.",
    "issue_date": "2021-08-12",
    "expiry_date": "2026-08-12"
  },
  "changed_fields": ["created"]
}
```

Delete activity shape:

```json
{
  "original": {
    "title": "Passport scan",
    "status": "active",
    "category_id": "uuid",
    "tag_ids": ["uuid"],
    "notes": "Stored for travel renewals.",
    "issue_date": "2021-08-12",
    "expiry_date": "2026-08-12"
  },
  "updated": null,
  "changed_fields": ["deleted"]
}
```

Bulk and reminder activity can include operation context alongside snapshots:

```json
{
  "bulk_action_id": "uuid",
  "source": "documents.index",
  "original": {
    "title": "Passport scan",
    "status": "active",
    "category_id": "uuid",
    "tag_ids": ["uuid"],
    "notes": "Stored for travel renewals.",
    "issue_date": "2021-08-12",
    "expiry_date": "2026-08-12"
  },
  "updated": {
    "title": "Passport scan",
    "status": "archived",
    "category_id": "uuid",
    "tag_ids": ["uuid"],
    "notes": "Stored for travel renewals.",
    "issue_date": "2021-08-12",
    "expiry_date": "2026-08-12"
  },
  "changed_fields": ["status"]
}
```

Snapshot rules:

- Do not store private file paths, OAuth/provider data, or binary content in metadata.
- Include user-editable document fields and relationship ids needed to understand the change.
- Keep derived display fields, such as category and tag names, only when they improve timeline readability.
- If metadata grows too large, prefer storing only the document fields shown in the UI rather than the full Eloquent
  model payload.

### UX Copy

- Keep messages action-oriented and short.
- Use flash messages for successful bulk actions and saved filter changes.
- For reminders, make the email subject specific: `Document expiring soon: {title}`.

### Testing Strategy

Run the smallest relevant tests while building each feature, then run the full suite at the end.

- After PHP changes: `vendor/bin/pint --dirty --format agent`
- MongoDB-dependent tests must use a dedicated test database/collection and clean it between tests.
- Use `Event::fake()` to assert document workflow events are dispatched.
- Test the queued activity listener directly so MongoDB writes are covered without relying only on queue execution.
- Use `Queue::fake()` where the assertion is that the listener/job is queued after commit.
- Feature-specific tests: `php artisan test --compact --filter=<testName>`
- Full suite before completion: `php artisan test --compact`
- For mobile preview and major document index UI changes: targeted Dusk tests
- Frontend build after Vue changes: `npm run build`

## Suggested Implementation Order

1. MongoDB package/configuration/local infrastructure.
2. Laravel document activity events, queued listener, payload factory, and MongoDB activity writer.
3. Document activity history show-page UI.
4. Reminder data model, sync service, scheduled command, email, and reminder activity event.
5. Saved filter models, routes, and document index controls.
6. Bulk actions backend, index selection UI, and activity event integration.
7. Mobile preview refinement and Dusk coverage.

## Acceptance Checklist

- MongoDB is configured for local, test, and deployment environments.
- Document workflows dispatch Laravel activity events after PostgreSQL commits.
- Queued listeners create MongoDB activity records for document create/update/archive/restore/delete and reminder sends.
- Users can only view their own document activity.
- Reminder rows are generated from expiry dates and queued email reminders send once.
- Saved filter views can be created, applied, renamed, deleted, and scoped per user.
- Bulk archive, restore, and delete work for selected documents only.
- Bulk delete removes private local files.
- Mobile document preview is usable without exposing private file URLs.
- All new backend behavior has PHPUnit coverage.
- High-value UI workflows have Dusk coverage.
- `php artisan test --compact` and `npm run build` pass.
