<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach (UserRole::cases() as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role->value],
                [
                    'name' => $role->name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}
