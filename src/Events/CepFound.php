<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Events;

use Illuminate\Foundation\Events\Dispatchable;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;

class CepFound
{
    use Dispatchable;

    public function __construct(
        public readonly string    $cep,
        public readonly CepEntity $entity,
    ) {
    }
}
