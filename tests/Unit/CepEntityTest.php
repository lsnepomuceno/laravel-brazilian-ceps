<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;

test(
    'validates cep entity structure',
    function ($city, $cep, $street, $state, $uf, $neighborhood, $number, $complement, $ibge) {
        $cepEntity = new CepEntity(
            city: $city,
            cep: $cep,
            street: $street,
            state: $state,
            uf: $uf,
            neighborhood: $neighborhood,
            number: $number,
            complement: $complement,
            ibge: $ibge
        );

        expect($cepEntity->toArray())->toBeArray();
    }
)->with([
    'default cep entity structure' => [
        'city', 'cep', 'street', 'state', 'uf', 'neighborhood', 'number', 'complement', 'ibge'
    ],
    'cep entity structure with integer number' => [
        'city', 'cep', 'street', 'state', 'uf', 'neighborhood', 1000, 'complement', 'ibge'
    ],
    'cep entity structure when complement, number and ibge are null' => [
        'city', 'cep', 'street', 'state', 'uf', 'neighborhood', null, null, null
    ],
]);
