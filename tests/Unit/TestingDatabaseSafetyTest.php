<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestingDatabaseSafetyTest extends TestCase
{
    public function test_tests_use_file_backed_sqlite_databases(): void
    {
        $this->assertSame('sqlite', config('database.default'));

        $sqliteDatabasePath = $this->resolveDatabasePath((string) config('database.connections.sqlite.database'));
        $roadmapDatabasePath = $this->resolveDatabasePath((string) config('database.connections.roadmap.database'));

        $this->assertSame(database_path('testing.sqlite'), $sqliteDatabasePath);
        $this->assertSame(database_path('testing-roadmap.sqlite'), $roadmapDatabasePath);
        $this->assertFileExists(database_path('testing.sqlite'));
        $this->assertFileExists(database_path('testing-roadmap.sqlite'));
        $this->assertNotSame(
            DB::connection('sqlite')->getPdo(),
            DB::connection('roadmap')->getPdo(),
            'The roadmap connection should use its own file-backed sqlite database during tests.'
        );
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
