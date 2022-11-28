<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests;

use LSNepomuceno\LaravelBrazilianCeps\LaravelBrazilianCepsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
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
