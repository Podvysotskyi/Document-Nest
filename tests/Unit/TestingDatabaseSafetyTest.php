<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestingDatabaseSafetyTest extends TestCase
{
    public function test_tests_use_the_in_memory_sqlite_database(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        $this->assertSame(':memory:', config('database.connections.roadmap.database'));
        $this->assertSame(
            DB::connection('sqlite')->getPdo(),
            DB::connection('roadmap')->getPdo(),
            'The roadmap connection should share the same in-memory sqlite database during tests.'
        );
    }
}
