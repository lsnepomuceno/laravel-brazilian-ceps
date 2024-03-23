<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ConsultCepApiRouteTest extends TestCase
{
    public function testValidatesIfTheCepsQueryRouteIsAccessible()
    {
        $routename       = 'consult-cep.api';
        $routes          = collect(RouteFacade::getRoutes());
        $consultCepRoute = $routes->map(fn(Route $route) => $route->getName())
                                  ->first(fn(string $name) => $name === $routename);

        $this->assertNotEmpty($consultCepRoute);
    }

    public function testValidateTheReturnStructureOfTheRouteOnSuccess()
    {
        $response = $this->get('api/consult-cep/29018210');

        $response->assertStatus(200);
        $response->assertJsonStructure(
            DefaultValues::successfullyRequiredFields()
        );
    }

    public function testValidateTheReturnStructureOfTheRouteOnFailure()
    {
        $cepNotFoundMessage = config('brazilian-ceps.not_found_message');
        $response           = $this->get('api/consult-cep/66666666');

        $response->assertStatus(200);
        $response->assertJsonPath('failed', $cepNotFoundMessage);
    }
}
