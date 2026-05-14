<?php

namespace Tests\Unit\Policies;

use App\Models\Tag;
use App\Models\User;
use App\Policies\TagPolicy;
use Tests\TestCase;

class TagPolicyTest extends TestCase
{
    public function test_tag_policy_allows_view_any_and_create_for_authenticated_user(): void
    {
        $policy = new TagPolicy;
        $user = User::factory()->create();

        $this->assertTrue($policy->viewAny($user));
        $this->assertTrue($policy->create($user));
    }

    public function test_tag_policy_checks_ownership_for_model_actions(): void
    {
        $policy = new TagPolicy;
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $tag = Tag::factory()->for($owner)->create([
            'name' => 'important',
            'slug' => 'important',
        ]);

        $this->assertTrue($policy->view($owner, $tag));
        $this->assertTrue($policy->update($owner, $tag));
        $this->assertTrue($policy->delete($owner, $tag));
        $this->assertTrue($policy->restore($owner, $tag));
        $this->assertTrue($policy->forceDelete($owner, $tag));

        $this->assertFalse($policy->view($otherUser, $tag));
        $this->assertFalse($policy->update($otherUser, $tag));
        $this->assertFalse($policy->delete($otherUser, $tag));
        $this->assertFalse($policy->restore($otherUser, $tag));
        $this->assertFalse($policy->forceDelete($otherUser, $tag));
    }
}
