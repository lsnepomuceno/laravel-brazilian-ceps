<?php

use LSNepomuceno\LaravelBrazilianCeps\Enums\States;

test('is necessary to contain 27 Brazilian states', function () {
    $stateCases = States::cases() ?? [];
    $casesCount = count($stateCases);

    expect($stateCases)->toHaveCount($casesCount);
});

test('the state writing must be the same as defined in the enum', function ($uf, $expected) {
    $state = States::get($uf);
    
    expect($state)->toBe($expected);
})->with([
    'Acre' => ['AC', 'Acre'],
    'Alagoas' => ['AL', 'Alagoas'],
    'Amapá' => ['AP', 'Amapá'],
    'Amazonas' => ['AM', 'Amazonas'],
    'Bahia' => ['BA', 'Bahia'],
    'Ceará' => ['CE', 'Ceará'],
    'Distrito Federal' => ['DF', 'Distrito Federal'],
    'Espírito Santo' => ['ES', 'Espírito Santo'],
    'Goiás' => ['GO', 'Goiás'],
    'Maranhão' => ['MA', 'Maranhão'],
    'Mato Grosso' => ['MT', 'Mato Grosso'],
    'Mato Grosso do Sul' => ['MS', 'Mato Grosso do Sul'],
    'Minas Gerais' => ['MG', 'Minas Gerais'],
    'Pará' => ['PA', 'Pará'],
    'Paraíba' => ['PB', 'Paraíba'],
    'Paraná' => ['PR', 'Paraná'],
    'Pernambuco' => ['PE', 'Pernambuco'],
    'Piauí' => ['PI', 'Piauí'],
    'Rio de Janeiro' => ['RJ', 'Rio de Janeiro'],
    'Rio Grande do Norte' => ['RN', 'Rio Grande do Norte'],
    'Rio Grande do Sul' => ['RS', 'Rio Grande do Sul'],
    'Rondônia' => ['RO', 'Rondônia'],
    'Roraima' => ['RR', 'Roraima'],
    'Santa Catarina' => ['SC', 'Santa Catarina'],
    'São Paulo' => ['SP', 'São Paulo'],
    'Sergipe' => ['SE', 'Sergipe'],
    'Tocantins' => ['TO', 'Tocantins'],
]);
