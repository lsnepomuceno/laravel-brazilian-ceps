<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Facades;

use Illuminate\Support\Facades\Facade;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;

class CEP extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CepService::class;
    }
}
