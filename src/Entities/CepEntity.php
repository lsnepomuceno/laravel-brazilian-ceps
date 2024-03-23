<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Entities;

use Illuminate\Contracts\Support\Arrayable;

class CepEntity implements Arrayable
{
    public function __construct(
        public string          $city,
        public string          $cep,
        public string          $street,
        public string          $state,
        public string          $uf,
        public string          $neighborhood,
        public string|int|null $number = null,
        public ?string         $complement = null,
        public string|int|null $ibge = null
    )
    {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
