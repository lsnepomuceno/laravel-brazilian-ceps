<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests;

use LSNepomuceno\LaravelBrazilianCeps\LaravelBrazilianCepsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{

    public function setUp(): void
    {
        parent::setUp();

        config()->set('cache.default', 'array');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelBrazilianCepsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        //
    }
}
