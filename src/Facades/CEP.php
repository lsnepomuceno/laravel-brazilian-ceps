<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Facades;

use Illuminate\Support\Facades\Facade;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;

/**
 * @method static null|CepEntity get(string $cep)
 */
class CEP extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CepService::class;
    }
}
