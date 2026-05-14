<?php

namespace Tests\Unit\Observers;

use App\Models\User;
use Tests\TestCase;

class UserObserverTest extends TestCase
{
    public function test_user_observer_creates_default_categories_on_user_creation(): void
    {
        $user = User::factory()->create();

        $this->assertSame(9, $user->categories()->count());
        $this->assertEqualsCanonicalizing([
            'Finance',
            'Health',
            'Identity',
            'Home',
            'Vehicle',
            'Work',
            'Education',
            'Legal',
            'Other',
        ], $user->categories()->pluck('name')->all());
    }
}
