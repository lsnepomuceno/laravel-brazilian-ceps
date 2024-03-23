<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Contracts;

use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;

interface ConsultableCEPProvider
{
    public function get(string $cep): ?CepEntity;

    public function getBaseUrl(): string;
}
