<?php

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Policies\CategoryPolicy;

test('category policy allows viewAny and create for authenticated user', function () {
    $policy = new CategoryPolicy;
    $user = User::factory()->create();

    expect($policy->viewAny($user))->toBeTrue();
    expect($policy->create($user))->toBeTrue();
});

test('category policy checks ownership for model actions', function () {
    $policy = new CategoryPolicy;
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $category = Category::factory()->for($owner)->create([
        'name' => 'FinanceX',
        'slug' => 'finance-x',
    ]);

    expect($policy->view($owner, $category))->toBeTrue();
    expect($policy->update($owner, $category))->toBeTrue();
    expect($policy->delete($owner, $category))->toBeTrue();
    expect($policy->restore($owner, $category))->toBeTrue();
    expect($policy->forceDelete($owner, $category))->toBeTrue();

    expect($policy->view($otherUser, $category))->toBeFalse();
    expect($policy->update($otherUser, $category))->toBeFalse();
    expect($policy->delete($otherUser, $category))->toBeFalse();
    expect($policy->restore($otherUser, $category))->toBeFalse();
    expect($policy->forceDelete($otherUser, $category))->toBeFalse();
});

test('category policy denies delete when category has documents', function () {
    $policy = new CategoryPolicy;
    $owner = User::factory()->create();
    $category = Category::factory()->for($owner)->create([
        'name' => 'FinanceX',
        'slug' => 'finance-x',
    ]);

    Document::factory()->for($owner)->for($category)->create();

    expect($policy->delete($owner, $category))->toBeFalse();
});
