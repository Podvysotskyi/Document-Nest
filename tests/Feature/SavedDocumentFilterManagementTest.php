<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\SavedDocumentFilter;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SavedDocumentFilterManagementTest extends TestCase
{
    public function test_documents_index_receives_saved_filters_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        SavedDocumentFilter::factory()->for($user)->create([
            'name' => 'Z Archive',
            'filters' => ['status' => 'archived'],
        ]);
        SavedDocumentFilter::factory()->for($user)->default()->create([
            'name' => 'A Default',
            'filters' => ['q' => 'passport'],
        ]);
        SavedDocumentFilter::factory()->for($otherUser)->create([
            'name' => 'Private',
        ]);

        $this->actingAs($user)
            ->get(route('documents.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Documents/Index')
                ->has('savedFilters', 2)
                ->where('savedFilters.0.name', 'A Default')
                ->where('savedFilters.0.is_default', true)
                ->where('savedFilters.1.name', 'Z Archive')
            );
    }

    public function test_authenticated_user_can_create_saved_document_filter(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('documents.index', ['q' => 'passport']))
            ->post(route('document-filters.store'), [
                'name' => 'Travel docs',
                'filters' => [
                    'q' => ' passport ',
                    'category_id' => '',
                    'status' => 'active',
                    'unexpected' => 'ignored',
                ],
                'sort' => 'title',
                'direction' => 'asc',
                'is_default' => true,
            ])
            ->assertRedirect(route('documents.index', ['q' => 'passport']))
            ->assertSessionHas('success', 'Saved view created.');

        $savedFilter = SavedDocumentFilter::query()->sole();

        $this->assertSame($user->id, $savedFilter->user_id);
        $this->assertSame('Travel docs', $savedFilter->name);
        $this->assertSame([
            'q' => 'passport',
            'status' => 'active',
        ], $savedFilter->filters);
        $this->assertSame('title', $savedFilter->sort);
        $this->assertSame('asc', $savedFilter->direction);
        $this->assertTrue($savedFilter->is_default);
    }

    public function test_authenticated_user_can_update_saved_document_filter_and_make_it_default(): void
    {
        $user = User::factory()->create();
        $oldDefault = SavedDocumentFilter::factory()->for($user)->default()->create();
        $savedFilter = SavedDocumentFilter::factory()->for($user)->create([
            'name' => 'Old name',
        ]);

        $this->actingAs($user)
            ->patch(route('document-filters.update', $savedFilter), [
                'name' => 'Renewals',
                'filters' => [
                    'expiry_from' => '2026-05-01',
                    'expiry_to' => '2026-06-01',
                ],
                'sort' => 'expiry_date',
                'direction' => 'asc',
                'is_default' => true,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Saved view updated.');

        $this->assertFalse($oldDefault->fresh()->is_default);
        $this->assertTrue($savedFilter->fresh()->is_default);
        $this->assertSame('Renewals', $savedFilter->fresh()->name);
        $this->assertSame([
            'expiry_from' => '2026-05-01',
            'expiry_to' => '2026-06-01',
        ], $savedFilter->fresh()->filters);
    }

    public function test_authenticated_user_can_delete_saved_document_filter(): void
    {
        $user = User::factory()->create();
        $savedFilter = SavedDocumentFilter::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('document-filters.destroy', $savedFilter))
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success', 'Saved view deleted.');

        $this->assertModelMissing($savedFilter);
    }

    public function test_user_cannot_manage_another_users_saved_document_filter(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $savedFilter = SavedDocumentFilter::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->patch(route('document-filters.update', $savedFilter), [
                'name' => 'Blocked',
                'filters' => [],
            ])
            ->assertForbidden();

        $this->actingAs($user)
            ->delete(route('document-filters.destroy', $savedFilter))
            ->assertForbidden();
    }

    public function test_saved_document_filter_validation_rejects_invalid_query_state(): void
    {
        $user = User::factory()->create();
        SavedDocumentFilter::factory()->for($user)->create([
            'name' => 'Existing',
        ]);

        $this->actingAs($user)
            ->post(route('document-filters.store'), [
                'name' => 'Existing',
                'filters' => [
                    'status' => 'invalid',
                    'category_id' => 'not-a-uuid',
                ],
                'sort' => 'bad-sort',
                'direction' => 'sideways',
            ])
            ->assertSessionHasErrors(['name', 'filters.status', 'filters.category_id', 'sort', 'direction']);
    }

    public function test_guest_cannot_manage_saved_document_filters(): void
    {
        $savedFilter = SavedDocumentFilter::factory()->create();

        $this->post(route('document-filters.store'), [
            'name' => 'Travel docs',
            'filters' => [],
        ])->assertRedirect(route('login'));

        $this->patch(route('document-filters.update', $savedFilter), [
            'name' => 'Travel docs',
            'filters' => [],
        ])->assertRedirect(route('login'));

        $this->delete(route('document-filters.destroy', $savedFilter))->assertRedirect(route('login'));
    }

    public function test_saved_filter_query_state_can_be_applied_to_documents_index(): void
    {
        $user = User::factory()->create();
        Document::factory()->for($user)->create(['title' => 'Passport Scan']);
        Document::factory()->for($user)->create(['title' => 'Tax Return']);

        $savedFilter = SavedDocumentFilter::factory()->for($user)->create([
            'filters' => ['q' => 'Passport'],
            'sort' => 'title',
            'direction' => 'asc',
        ]);

        $this->actingAs($user)
            ->get(route('documents.index', [
                ...$savedFilter->filters,
                'sort' => $savedFilter->sort,
                'direction' => $savedFilter->direction,
            ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('documents.data', 1)
                ->where('documents.data.0.title', 'Passport Scan')
                ->where('filters.q', 'Passport')
            );
    }
}
