<?php

namespace Tests\Unit\Policies;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    public function test_category_policy_allows_view_any_and_create_for_authenticated_user(): void
    {
        $policy = new CategoryPolicy;
        $user = User::factory()->create();

        $this->assertTrue($policy->viewAny($user));
        $this->assertTrue($policy->create($user));
    }

    public function test_category_policy_checks_ownership_for_model_actions(): void
    {
        $policy = new CategoryPolicy;
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->for($owner)->create([
            'name' => 'FinanceX',
            'slug' => 'finance-x',
        ]);

        $this->assertTrue($policy->view($owner, $category));
        $this->assertTrue($policy->update($owner, $category));
        $this->assertTrue($policy->delete($owner, $category));
        $this->assertTrue($policy->restore($owner, $category));
        $this->assertTrue($policy->forceDelete($owner, $category));

        $this->assertFalse($policy->view($otherUser, $category));
        $this->assertFalse($policy->update($otherUser, $category));
        $this->assertFalse($policy->delete($otherUser, $category));
        $this->assertFalse($policy->restore($otherUser, $category));
        $this->assertFalse($policy->forceDelete($otherUser, $category));
    }

    public function test_category_policy_denies_delete_when_category_has_documents(): void
    {
        $policy = new CategoryPolicy;
        $owner = User::factory()->create();
        $category = Category::factory()->for($owner)->create([
            'name' => 'FinanceX',
            'slug' => 'finance-x',
        ]);

        Document::factory()->for($owner)->for($category)->create();

        $this->assertFalse($policy->delete($owner, $category));
    }
}
