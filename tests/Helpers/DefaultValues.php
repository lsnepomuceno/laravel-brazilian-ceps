<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers;

class DefaultValues
{
    public static function successfullyRequiredFields(): array
    {
        return [
            'city',
            'cep',
            'street',
            'uf',
            'neighborhood'
        ];
    }

    public static function optionalFields(): array
    {
        return [
            'number',
            'complement',
            'ibge'
        ];
    }
}
