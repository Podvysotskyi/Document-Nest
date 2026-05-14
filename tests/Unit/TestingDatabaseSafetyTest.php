<?php

namespace Tests\Unit;

use Tests\TestCase;

class TestingDatabaseSafetyTest extends TestCase
{
    public function test_tests_use_the_in_memory_sqlite_database(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
    }
}
