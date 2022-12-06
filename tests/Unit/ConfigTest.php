<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function testValidatesIfTheValuesAreInTheConfigFile()
    {
        $configValues = config('brazilian-ceps');

        $this->assertIsArray($configValues);

        $this->assertArrayHasKey('cache_results', $configValues);
        $this->assertIsBool($configValues['cache_results']);

        $this->assertArrayHasKey('cache_lifetime_in_days', $configValues);
        $this->assertIsNumeric($configValues['cache_lifetime_in_days']);

        $this->assertArrayHasKey('throw_not_found_exception', $configValues);
        $this->assertIsBool($configValues['throw_not_found_exception']);

        $this->assertArrayHasKey('enable_api_consult_cep_route', $configValues);
        $this->assertIsBool($configValues['enable_api_consult_cep_route']);

        $this->assertArrayHasKey('api_route_middleware', $configValues);
        $this->assertIsArray($configValues['api_route_middleware']);

        $this->assertArrayHasKey('not_found_message', $configValues);
        $this->assertIsString($configValues['not_found_message']);
    }

    public function testValidatesIfTrueAsDesfaultCacheResultValue()
    {
        $configValues = config('brazilian-ceps');
        $this->assertEquals(true, $configValues['cache_results']);
    }

    public function testValidatesIf_30AsDesfaultCacheLifetimeInDaysValue()
    {
        $configValues = config('brazilian-ceps');
        $this->assertEquals(30, $configValues['cache_lifetime_in_days']);
    }

    public function testValidatesIfFalseAsDesfaultThrowNotFoundExceptionValue()
    {
        $configValues = config('brazilian-ceps');
        $this->assertEquals(false, $configValues['throw_not_found_exception']);
    }

    public function testValidatesIfTrueAsDesfaultEnableApiConsultCepRouteValue()
    {
        $configValues = config('brazilian-ceps');
        $this->assertEquals(true, $configValues['enable_api_consult_cep_route']);
    }

    public function testValidatesIfGuestADefaultApiRouteMiddlewareValue()
    {
        $configValues = config('brazilian-ceps');
        $this->assertEquals(['guest'], $configValues['api_route_middleware']);
    }

    public function testValidatesIfCepNaoEncontradoAsDesfaultNotFoundMessageValue()
    {
        $configValues = config('brazilian-ceps');
        $this->assertEquals('CEP não encontrado.', $configValues['not_found_message']);
    }
}
