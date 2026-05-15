<?php

namespace Tests\Unit\Listeners;

use App\DTOs\DocumentActivityPayload;
use App\Enums\DocumentActivityType;
use App\Events\Documents\DocumentCreated;
use App\Listeners\CreateDocumentActivity;
use App\Services\DocumentActivityService;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Support\Facades\Log;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class CreateDocumentActivityTest extends TestCase
{
    public function test_listener_implements_should_queue_after_commit_contract(): void
    {
        $this->assertInstanceOf(
            ShouldQueueAfterCommit::class,
            $this->app->make(CreateDocumentActivity::class),
        );
    }

    public function test_listener_records_payload_via_activity_service(): void
    {
        $service = Mockery::mock(DocumentActivityService::class);
        $payload = $this->makePayload();
        $service->shouldReceive('record')->once()->with(Mockery::on(
            fn (DocumentActivityPayload $passed): bool => $passed === $payload,
        ));

        (new CreateDocumentActivity($service))->handle(new DocumentCreated($payload));
    }

    public function test_listener_logs_and_swallows_recording_failure_to_keep_caller_resilient(): void
    {
        $service = Mockery::mock(DocumentActivityService::class);
        $payload = $this->makePayload();
        $service->shouldReceive('record')->once()->andThrow(new RuntimeException('mongo down'));

        $logged = false;
        Log::partialMock()->shouldReceive('error')->andReturnUsing(function (string $message, array $context = []) use ($payload, &$logged): void {
            if ($message === 'Failed to record document activity'
                && ($context['document_id'] ?? null) === $payload->documentId
                && ($context['type'] ?? null) === $payload->type->value
                && ($context['exception'] ?? null) === 'mongo down'
            ) {
                $logged = true;
            }
        });

        (new CreateDocumentActivity($service))->handle(new DocumentCreated($payload));

        $this->assertTrue($logged, 'Expected the listener to log the recording failure context.');
    }

    private function makePayload(): DocumentActivityPayload
    {
        return new DocumentActivityPayload(
            documentId: 'doc-uuid',
            userId: 'user-uuid',
            actorId: 'user-uuid',
            documentTitle: 'Title',
            type: DocumentActivityType::Created,
            description: 'Document created',
            metadata: ['original' => null, 'updated' => ['title' => 'Title']],
            activityId: 'activity-uuid',
        );
    }
}
