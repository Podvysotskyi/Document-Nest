# Document Nest Next Roadmap Plan

This plan covers the remaining work in the current product slice:

- Email reminders for documents expiring soon
- Bulk document actions UI
- Improved mobile document preview experience

Document activity history shipped earlier; new features in this slice continue to write to the activity store described
under [Reference: Activity Infrastructure](#reference-activity-infrastructure).

The implementation should keep the current app shape: Laravel controllers with Form Request authorization, service and
repository classes for domain behavior, policies for user ownership, Inertia Vue pages under `resources/js/Pages`, and
focused PHPUnit coverage for every change.

PostgreSQL remains the source of truth for users, documents, categories, tags, reminders, and authorization-critical
data. MongoDB is reserved for the append-only activity store.

## Goals

1. Help users act before important documents expire.
2. Make repeated document maintenance efficient through bulk actions.
3. Make document preview usable on mobile screens without breaking private storage.

## Non-Goals

- No multi-user sharing or household collaboration in this slice.
- No external cloud storage migration; keep private local storage behind authorized routes.
- No SMS, push, or calendar integrations yet.
- No full-text search engine dependency yet; continue using the current database-backed search.
- No migration of core application data to MongoDB. MongoDB is only for document activity history in this slice.

## Reference: Activity Infrastructure

Activity history infrastructure is in place. New features in this slice extend it rather than introduce new
infrastructure. The pieces below are listed so Phase 1 (reminders) can add events without re-deriving conventions.

- Connection: `mongodb` connection in `config/database.php`, env vars `MONGODB_DSN` and `MONGODB_DATABASE`. Local
  `docker-compose.yml` provisions a MongoDB container.
- Storage: `document_activities` MongoDB collection. `App\Models\Mongodb\DocumentActivity` pins
  `protected $connection = 'mongodb'`. The PHP extension `ext-mongodb` is required at runtime.
- Boundary: PostgreSQL stays authoritative for documents and authorization. Activity reads must be preceded by a
  PostgreSQL ownership check (currently via `DocumentPolicy::view`). Activity writes are best-effort — failures log and
  report but do not roll back the core action.
- Event contract: events live under `App\Events\Documents`, extend `App\Events\Documents\DocumentActivityEvent`, and
  carry a single immutable `App\DTOs\DocumentActivityPayload` value object (`document_id`, `user_id`, `actor_id`,
  `document_title`, `type`, `description`, `metadata`, `activity_id`).
- Payload construction: `App\Services\DocumentActivityPayloadFactory` builds payloads with sanitized
  `metadata.original` / `metadata.updated` snapshots and a `metadata.changed_fields` array. Add a `for<EventName>`
  method on the factory when introducing a new activity type.
- Writer: `App\Listeners\CreateDocumentActivity` (implements `ShouldQueue`) is the only writer. It delegates to
  `App\Services\DocumentActivityService::record()` which upserts on `activity_id` for idempotency. Register new events
  with this listener in `AppServiceProvider::registerDocumentActivityListeners()`.
- Read path: `App\Repositories\DocumentActivityRepository::paginateForDocument()` is user-scoped and paginated; the
  Show page consumes it as a deferred Inertia prop.
- New activity types: extend `App\Enums\DocumentActivityType` (already includes `ReminderSent`, `BulkArchived`,
  `BulkRestored`, `BulkDeleted`, etc.) rather than hardcoding strings.

## Status

All phases of this slice are shipped: document activity history, email reminders for expiring documents, bulk actions
UI, and mobile preview refinements. The sections below document the conventions to follow when extending these
features in future slices.

## Cross-Cutting Requirements

### Authorization and Privacy

- Every new route must be inside `auth` middleware.
- Every document-scoped action must verify ownership through policy or Form Request validation.
- Reminders and activities must be user-scoped.
- Do not expose local storage paths to the frontend.
- MongoDB activity reads must be preceded by PostgreSQL document authorization.
- MongoDB documents must not store file paths, provider tokens, or other secrets.

### Performance

- Eager load relationships used by list resources.
- Bulk actions should process only the selected ids for this slice.

### Activity Metadata

When adding new activity events, follow the existing snapshot conventions implemented by
`DocumentActivityPayloadFactory`:

- For mutations, store full sanitized `original` and `updated` snapshots plus a `changed_fields` array. Create events
  use `original: null`; delete events use `updated: null`.
- Include only user-editable document fields and the relationship ids needed to understand the change. Never include
  file paths, OAuth/provider data, or binary content.
- Add operation context (e.g. `bulk_action_id`, `source`, reminder `remind_on`) at the top level of `metadata`
  alongside the snapshot keys.
- Keep payloads small — store only what the timeline UI needs, not full Eloquent payloads.

### UX Copy

- Keep messages action-oriented and short.
- Use flash messages for successful bulk actions.
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

## Standing Acceptance Bar

Any future change in this area should preserve these guarantees:

- All new backend behavior has PHPUnit coverage.
- High-value UI workflows have Dusk coverage.
- `php artisan test --compact` and `npm run build` pass.
