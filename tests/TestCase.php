<?php

namespace Tests;

use Illuminate\Database\Connection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * @var array<int, string>
     */
    protected array $connectionsToTransact = ['sqlite'];

    protected bool $seed = true;

    public function createApplication(): Application
    {
        $this->forceSqliteTestingEnvironment();

        $app = parent::createApplication();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.foreign_key_constraints', true);
        $app['config']->set('database.connections.roadmap.database', ':memory:');
        $app['config']->set('database.connections.roadmap.foreign_key_constraints', true);

        $this->useSingleSqliteTestingDatabase($app);

        $this->ensureTestingDatabaseIsSafe($app);

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->withHeader('Sec-Fetch-Site', 'same-origin');
    }

    private function forceSqliteTestingEnvironment(): void
    {
        foreach ([
            'APP_ENV' => 'testing',
            'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'DB_URL' => '',
            'ROADMAP_DB_DATABASE' => ':memory:',
        ] as $key => $value) {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }

    private function useSingleSqliteTestingDatabase(Application $app): void
    {
        $app['db']->purge('sqlite');
        $app['db']->purge('roadmap');
        $app['db']->extend('roadmap', fn (): Connection => $app['db']->connection('sqlite'));
    }

    private function ensureTestingDatabaseIsSafe(Application $app): void
    {
        if ($app['config']->get('database.default') !== 'sqlite') {
            throw new RuntimeException('Tests must run against the sqlite database connection.');
        }

        if ($app['config']->get('database.connections.sqlite.database') !== ':memory:') {
            throw new RuntimeException('Tests must run against an in-memory sqlite database.');
        }

        if ($app['config']->get('database.connections.roadmap.database') !== ':memory:') {
            throw new RuntimeException('Tests must run against an in-memory roadmap sqlite database.');
        }

        if ($app['db']->connection('sqlite')->getPdo() !== $app['db']->connection('roadmap')->getPdo()) {
            throw new RuntimeException('Tests must use a single in-memory sqlite database for all model connections.');
        }
    }
}
