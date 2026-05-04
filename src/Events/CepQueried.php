<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Events;

use Illuminate\Foundation\Events\Dispatchable;

class CepQueried
{
    use Dispatchable;

    public function __construct(public readonly string $cep)
    {
    }
}
