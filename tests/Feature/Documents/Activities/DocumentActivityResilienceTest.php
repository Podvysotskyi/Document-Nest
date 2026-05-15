<?php

namespace Tests\Feature\Documents\Activities;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Services\DocumentActivityService;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class DocumentActivityResilienceTest extends TestCase
{
    public function test_document_update_succeeds_when_activity_recording_fails(): void
    {
        $failingService = Mockery::mock(DocumentActivityService::class);
        $failingService->shouldReceive('record')->andThrow(new RuntimeException('mongo down'));
        $this->app->instance(DocumentActivityService::class, $failingService);

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'title' => 'Old Title',
            'status' => DocumentStatus::Active,
        ]);

        $this->actingAs($user)->put(route('documents.update', $document), [
            'title' => 'New Title',
            'category_id' => $document->category_id,
            'status' => DocumentStatus::Active->value,
        ])->assertRedirect(route('documents.show', $document));

        $this->assertSame('New Title', $document->fresh()->title);
    }
}
