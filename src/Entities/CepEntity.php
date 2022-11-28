<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Entities;

use Illuminate\Contracts\Support\Arrayable;

class CepEntity implements Arrayable
{
    public function __construct(
        protected string          $city,
        protected string          $cep,
        protected string          $street,
        protected string          $state,
        protected string          $uf,
        protected string          $neighborhood,
        protected string|int|null $number = null,
        protected ?string         $complement = null
    )
    {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
