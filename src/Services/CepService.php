<?php

namespace App\Services\Cep;

use Illuminate\Support\Traits\Conditionable;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ApiCep;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV1;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV2;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\CepLa;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\OpenCep;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ViaCep;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;

class CepService
{
    use Conditionable;

    public function __construct(
        protected ?CepEntity $cepEntity,
        protected array      $cepApis = [
            ViaCep::class,
            OpenCep::class,
            ApiCep::class,
            CepLa::class,
            BrasilApiV1::class,
            BrasilApiV2::class
        ]
    )
    {
    }

    public function get(string $cep): CepEntity
    {
        foreach ($this->cepApis as $cepApi) {
            $this->when(
                !$this->cepEntity?->cep,
                fn() => $this->cepEntity = (new $cepApi)->get($cep)
            );
        }
        // TODO: validar se deve retornar exception via config
        return $this->cepEntity;
    }
}
