<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use LSNepomuceno\LaravelBrazilianCeps\LaravelBrazilianCepsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

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
}
