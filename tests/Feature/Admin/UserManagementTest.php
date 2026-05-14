<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Document;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    public function test_non_admin_user_cannot_view_user_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_admin_user_can_view_user_management(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Admin User']);
        $user = User::factory()->create(['name' => 'Managed User']);

        Document::factory()->count(2)->for($user)->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Users/Index')
                ->has('users.data', 2)
                ->where('users.data.1.name', 'Managed User')
                ->where('users.data.1.documents_count', 2)
                ->has('roles', 2)
            );
    }

    public function test_admin_user_can_grant_admin_access_to_another_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $adminRoleId = $this->adminRoleId();

        $this->actingAs($admin)
            ->patch(route('admin.users.roles.update', $user), [
                'role_ids' => [$adminRoleId],
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue($user->fresh()->hasRole(UserRole::Admin));
    }

    public function test_admin_user_can_remove_admin_access_from_another_user(): void
    {
        $admin = User::factory()->admin()->create();
        $otherAdmin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.roles.update', $otherAdmin), [
                'role_ids' => [],
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertFalse($otherAdmin->fresh()->hasRole(UserRole::Admin));
    }

    public function test_admin_user_cannot_remove_their_own_admin_access(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.roles.update', $admin), [
                'role_ids' => [],
            ])
            ->assertSessionHasErrors('role_ids');

        $this->assertTrue($admin->fresh()->hasRole(UserRole::Admin));
    }

    public function test_admin_user_can_assign_multiple_roles(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.roles.update', $user), [
                'role_ids' => [$this->adminRoleId(), $this->developerRoleId()],
            ])
            ->assertRedirect(route('admin.users.index'));

        $fresh = $user->fresh()->load('roles');
        $this->assertTrue($fresh->hasRole(UserRole::Admin));
        $this->assertTrue($fresh->hasRole(UserRole::Developer));
    }

    private function adminRoleId(): int
    {
        return UserRole::Admin->value;
    }

    private function developerRoleId(): int
    {
        return UserRole::Developer->value;
    }
}
