<?php

namespace Tests\Unit\Policies;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Policies\DocumentPolicy;
use Tests\TestCase;

class DocumentPolicyTest extends TestCase
{
    public function test_document_policy_allows_view_any_and_create_for_authenticated_user(): void
    {
        $policy = new DocumentPolicy;
        $user = User::factory()->create();

        $this->assertTrue($policy->viewAny($user));
        $this->assertTrue($policy->create($user));
    }

    public function test_document_policy_checks_ownership_for_model_actions(): void
    {
        $policy = new DocumentPolicy;
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $document = Document::factory()->for($owner)->create([
            'title' => 'Passport',
            'status' => DocumentStatus::Active,
            'original_filename' => 'passport.pdf',
            'stored_path' => 'documents/passport.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1000,
        ]);

        $this->assertTrue($policy->view($owner, $document));
        $this->assertTrue($policy->update($owner, $document));
        $this->assertTrue($policy->delete($owner, $document));
        $this->assertTrue($policy->restore($owner, $document));
        $this->assertTrue($policy->forceDelete($owner, $document));

        $this->assertFalse($policy->view($otherUser, $document));
        $this->assertFalse($policy->update($otherUser, $document));
        $this->assertFalse($policy->delete($otherUser, $document));
        $this->assertFalse($policy->restore($otherUser, $document));
        $this->assertFalse($policy->forceDelete($otherUser, $document));
    }
}
