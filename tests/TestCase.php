<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    private const TEST_DATABASE_FILE = 'testing.sqlite';

    private const TEST_ROADMAP_DATABASE_FILE = 'testing-roadmap.sqlite';

    /**
     * @var array<int, string>
     */
    protected array $connectionsToTransact = ['sqlite', 'roadmap'];

    protected bool $seed = true;

    public function createApplication(): Application
    {
        $this->ensureFileSqliteTestingEnvironment();

        $app = parent::createApplication();

        $this->ensureTestingDatabaseIsSafe($app);

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->withHeader('Sec-Fetch-Site', 'same-origin');
    }

    protected function beforeRefreshingDatabase(): void
    {
        if (RefreshDatabaseState::$migrated) {
            return;
        }

        $this->artisan('db:wipe', [
            '--database' => 'sqlite',
            '--force' => true,
        ]);

        $this->artisan('db:wipe', [
            '--database' => 'roadmap',
            '--force' => true,
        ]);
    }

    private function ensureFileSqliteTestingEnvironment(): void
    {
        self::ensureSqliteDatabaseExists(self::sqliteTestingDatabasePath());
        self::ensureSqliteDatabaseExists(self::roadmapTestingDatabasePath());
    }

    private static function ensureSqliteDatabaseExists(string $databasePath): void
    {
        $databaseDirectory = dirname($databasePath);

        if (! is_dir($databaseDirectory)) {
            mkdir($databaseDirectory, 0755, true);
        }

        if (! file_exists($databasePath)) {
            touch($databasePath);
        }
    }

    private static function sqliteTestingDatabasePath(): string
    {
        return self::databasePath(self::TEST_DATABASE_FILE);
    }

    private static function roadmapTestingDatabasePath(): string
    {
        return self::databasePath(self::TEST_ROADMAP_DATABASE_FILE);
    }

    private static function databasePath(string $filename): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.$filename;
    }

    private function ensureTestingDatabaseIsSafe(Application $app): void
    {
        if ($app['config']->get('database.default') !== 'sqlite') {
            throw new RuntimeException('Tests must run against the sqlite database connection.');
        }

        $sqliteDatabasePath = self::resolveDatabasePath((string) $app['config']->get('database.connections.sqlite.database'));

        if ($sqliteDatabasePath !== self::sqliteTestingDatabasePath()) {
            throw new RuntimeException('Tests must run against the file-backed sqlite testing database.');
        }

        $roadmapDatabasePath = self::resolveDatabasePath((string) $app['config']->get('database.connections.roadmap.database'));

        if ($roadmapDatabasePath !== self::roadmapTestingDatabasePath()) {
            throw new RuntimeException('Tests must run against the file-backed roadmap sqlite testing database.');
        }

        if ($app['db']->connection('sqlite')->getPdo() === $app['db']->connection('roadmap')->getPdo()) {
            throw new RuntimeException('Tests must use separate file-backed sqlite databases for app and roadmap connections.');
        }
    }

    private static function resolveDatabasePath(string $databasePath): string
    {
        if ($databasePath === ':memory:' || $databasePath === '') {
            return $databasePath;
        }

        if (str_starts_with($databasePath, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:[\\\\\\/]/', $databasePath) === 1) {
            return $databasePath;
        }

        return dirname(__DIR__).DIRECTORY_SEPARATOR.$databasePath;
    }
}
