<?php

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\User;
use App\Policies\DocumentPolicy;

test('document policy allows viewAny and create for authenticated user', function () {
    $policy = new DocumentPolicy;
    $user = User::factory()->create();

    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->create($user))->toBeTrue();
});

test('document policy checks ownership for model actions', function () {
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

    expect($policy->view($owner, $document))->toBeTrue();
    expect($policy->update($owner, $document))->toBeTrue();
    expect($policy->delete($owner, $document))->toBeTrue();
    expect($policy->restore($owner, $document))->toBeTrue();
    expect($policy->forceDelete($owner, $document))->toBeTrue();

    expect($policy->view($otherUser, $document))->toBeFalse();
    expect($policy->update($otherUser, $document))->toBeFalse();
    expect($policy->delete($otherUser, $document))->toBeFalse();
    expect($policy->restore($otherUser, $document))->toBeFalse();
    expect($policy->forceDelete($otherUser, $document))->toBeFalse();
});
