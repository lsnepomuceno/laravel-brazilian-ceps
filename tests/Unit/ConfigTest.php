<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

test(
    'the defined settings must follow the initial pattern',
    function ($key, $expectedType, $expectedValue = null) {
        $configValues = config('brazilian-ceps');

        expect($configValues)->toBeArray()
            ->and($configValues)->toHaveKey($key);

        if ($expectedType) {
            expect($configValues[$key])->toBeType($expectedType);
        }

        if (!is_null($expectedValue)) {
            expect($configValues[$key])->toBe($expectedValue);
        }
    }
)->with([
    'cache_results é booleano' => ['cache_results', 'bool'],
    'cache_lifetime_in_days é numérico' => ['cache_lifetime_in_days', 'numeric'],
    'throw_not_found_exception é booleano' => ['throw_not_found_exception', 'bool'],
    'enable_api_consult_cep_route é booleano' => ['enable_api_consult_cep_route', 'bool'],
    'api_route_middleware é array' => ['api_route_middleware', 'array'],
    'not_found_message é string' => ['not_found_message', 'string'],

    'cache_results valor padrão é true' => ['cache_results', 'bool', true],
    'cache_lifetime_in_days valor padrão é 30' => ['cache_lifetime_in_days', 'numeric', 30],
    'throw_not_found_exception valor padrão é false' => ['throw_not_found_exception', 'bool', false],
    'enable_api_consult_cep_route valor padrão é true' => ['enable_api_consult_cep_route', 'bool', true],
    'api_route_middleware valor padrão é guest' => ['api_route_middleware', 'array', ['guest']],
    'not_found_message valor padrão é CEP não encontrado' => ['not_found_message', 'string', 'CEP não encontrado.'],
]);
