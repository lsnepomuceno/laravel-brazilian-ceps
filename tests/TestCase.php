<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use LSNepomuceno\LaravelBrazilianCeps\LaravelBrazilianCepsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * @method artisan
 */
class TestCase extends Orchestra
{
    use WithFaker;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelBrazilianCepsServiceProvider::class,
        ];
    }

    protected function setUpFaker(): void
    {
        $this->faker = $this->makeFaker('pt_BR');
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.env', 'testing');
        $app['config']->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations(): void
    {
        if ((int) app()->version() >= 11) {
            Artisan::call('make:cache-table');
        }
        Artisan::call('migrate');
    }
}
