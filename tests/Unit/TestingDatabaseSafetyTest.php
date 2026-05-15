<?php

namespace Tests\Unit;

use Tests\TestCase;

class TestingDatabaseSafetyTest extends TestCase
{
    public function test_tests_use_file_backed_sqlite_database(): void
    {
        $this->assertSame('sqlite', config('database.default'));

        $sqliteDatabasePath = $this->resolveDatabasePath((string) config('database.connections.sqlite.database'));

        $this->assertSame(database_path('testing.sqlite'), $sqliteDatabasePath);
        $this->assertFileExists(database_path('testing.sqlite'));
    }

    private function resolveDatabasePath(string $databasePath): string
    {
        if ($databasePath === ':memory:' || $databasePath === '') {
            return $databasePath;
        }

        if (str_starts_with($databasePath, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:[\\\\\\/]/', $databasePath) === 1) {
            return $databasePath;
        }

        return base_path($databasePath);
    }
}
