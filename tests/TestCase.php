<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function createApplication(): Application
    {
        $this->forceSqliteTestingEnvironment();

        $app = parent::createApplication();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.foreign_key_constraints', true);
        $app['db']->purge();

        $this->ensureTestingDatabaseIsSafe($app);

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeader('Sec-Fetch-Site', 'same-origin');
    }

    private function forceSqliteTestingEnvironment(): void
    {
        foreach ([
            'APP_ENV' => 'testing',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'DB_URL' => '',
        ] as $key => $value) {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }

    private function ensureTestingDatabaseIsSafe(Application $app): void
    {
        if ($app['config']->get('database.default') !== 'sqlite') {
            throw new RuntimeException('Tests must run against the sqlite database connection.');
        }

        if ($app['config']->get('database.connections.sqlite.database') !== ':memory:') {
            throw new RuntimeException('Tests must run against an in-memory sqlite database.');
        }
    }
}
