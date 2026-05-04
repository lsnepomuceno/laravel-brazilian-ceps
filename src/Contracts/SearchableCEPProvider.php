<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Contracts;

use Illuminate\Support\Collection;

interface SearchableCEPProvider
{
    public function search(string $uf, string $city, string $street): Collection;
}
