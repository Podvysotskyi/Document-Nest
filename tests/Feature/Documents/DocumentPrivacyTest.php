<?php

namespace Tests\Feature\Documents;

use App\Models\Document;
use App\Models\User;
use Tests\TestCase;

class DocumentPrivacyTest extends TestCase
{
    public function test_show_response_never_exposes_local_stored_path(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'stored_path' => 'documents/super-secret-path.pdf',
        ]);

        $response = $this->actingAs($user)->get(route('documents.show', $document));

        $response->assertStatus(200);
        $response->assertDontSee('super-secret-path.pdf');
        $response->assertDontSee('stored_path');
    }

    public function test_index_response_never_exposes_local_stored_path(): void
    {
        $user = User::factory()->create();
        Document::factory()->for($user)->create([
            'stored_path' => 'documents/index-secret-path.pdf',
        ]);

        $response = $this->actingAs($user)->get(route('documents.index'));

        $response->assertStatus(200);
        $response->assertDontSee('index-secret-path.pdf');
        $response->assertDontSee('stored_path');
    }

    public function test_preview_route_rejects_unauthorized_users(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $document = Document::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('documents.preview', $document))
            ->assertStatus(403);
    }

    public function test_download_route_rejects_unauthorized_users(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $document = Document::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('documents.download', $document))
            ->assertStatus(403);
    }
}
