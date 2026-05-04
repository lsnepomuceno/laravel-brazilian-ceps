<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Events;

use Illuminate\Foundation\Events\Dispatchable;

class CepNotFound
{
    use Dispatchable;

    public function __construct(public readonly string $cep)
    {
    }
}
