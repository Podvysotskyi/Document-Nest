<?php

use App\Models\Tag;
use App\Models\User;
use App\Policies\TagPolicy;

test('tag policy allows viewAny and create for authenticated user', function () {
    $policy = new TagPolicy;
    $user = User::factory()->create();

    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->create($user))->toBeTrue();
});

test('tag policy checks ownership for model actions', function () {
    $policy = new TagPolicy;
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $tag = Tag::factory()->for($owner)->create([
        'name' => 'important',
        'slug' => 'important',
    ]);

    expect($policy->view($owner, $tag))->toBeTrue();
    expect($policy->update($owner, $tag))->toBeTrue();
    expect($policy->delete($owner, $tag))->toBeTrue();
    expect($policy->restore($owner, $tag))->toBeTrue();
    expect($policy->forceDelete($owner, $tag))->toBeTrue();

    expect($policy->view($otherUser, $tag))->toBeFalse();
    expect($policy->update($otherUser, $tag))->toBeFalse();
    expect($policy->delete($otherUser, $tag))->toBeFalse();
    expect($policy->restore($otherUser, $tag))->toBeFalse();
    expect($policy->forceDelete($otherUser, $tag))->toBeFalse();
});
