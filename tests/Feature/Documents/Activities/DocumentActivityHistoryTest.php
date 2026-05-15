<?php

namespace Tests\Feature\Documents\Activities;

use App\Enums\DocumentActivityType;
use App\Models\Document;
use App\Models\Mongodb\DocumentActivity;
use App\Models\User;
use App\Repositories\DocumentActivityRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class DocumentActivityHistoryTest extends TestCase
{
    public function test_owner_loads_activities_via_deferred_prop(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        $this->bindRepositoryReturning($document, [
            $this->makeActivity($document, $user, DocumentActivityType::Created, 'Document created'),
        ]);

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Documents/Show')
                ->loadDeferredProps('default', fn (Assert $partial) => $partial
                    ->has('activities.data', 1)
                    ->where('activities.data.0.type', DocumentActivityType::Created->value)
                    ->where('activities.data.0.label', DocumentActivityType::Created->label())
                    ->where('activities.data.0.description', 'Document created')
                )
            );
    }

    public function test_initial_show_response_does_not_evaluate_deferred_activity_prop(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();

        $repository = Mockery::mock(DocumentActivityRepository::class);
        $repository->shouldNotReceive('paginateForDocument');
        $this->app->instance(DocumentActivityRepository::class, $repository);

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertStatus(200);
    }

    public function test_other_user_cannot_access_document_activity_view(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $document = Document::factory()->for($owner)->create();

        $repository = Mockery::mock(DocumentActivityRepository::class);
        $repository->shouldNotReceive('paginateForDocument');
        $this->app->instance(DocumentActivityRepository::class, $repository);

        $this->actingAs($intruder)
            ->get(route('documents.show', $document))
            ->assertStatus(403);
    }

    /**
     * @param  array<int, DocumentActivity>  $activities
     */
    private function bindRepositoryReturning(Document $document, array $activities): void
    {
        $paginator = new LengthAwarePaginator(
            $activities,
            count($activities),
            20,
        );

        $repository = Mockery::mock(DocumentActivityRepository::class);
        $repository->shouldReceive('paginateForDocument')
            ->once()
            ->with(Mockery::on(fn (Document $passed): bool => $passed->id === $document->id))
            ->andReturn($paginator);

        $this->app->instance(DocumentActivityRepository::class, $repository);
    }

    private function makeActivity(Document $document, User $user, DocumentActivityType $type, string $description): DocumentActivity
    {
        $activity = new DocumentActivity([
            'document_id' => (string) $document->id,
            'user_id' => (string) $user->id,
            'actor_id' => (string) $user->id,
            'document_title' => $document->title,
            'type' => $type,
            'description' => $description,
            'metadata' => [],
            'activity_id' => 'fixture-'.$type->value,
        ]);

        $activity->setAttribute('_id', 'fixture-'.$type->value);
        $activity->setAttribute('created_at', Carbon::now());
        $activity->setAttribute('updated_at', Carbon::now());

        return $activity;
    }
}
