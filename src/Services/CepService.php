<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Conditionable;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ApiCep;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV1;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV2;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\CepLa;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\OpenCep;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\Pagarme;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ViaCep;
use Lsnepomuceno\LaravelBrazilianCeps\Contracts\ConsultableCEPProvider;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;

class CepService
{
    use Conditionable;

    public function __construct(
        protected ?CepEntity $cepEntity = null,
        protected array      $cepApis = [
            ViaCep::class,
            Pagarme::class,
            OpenCep::class,
            ApiCep::class,
            CepLa::class,
            BrasilApiV1::class,
            BrasilApiV2::class
        ]
    )
    {
    }

    /**
     * @throws CepNotFoundException
     */
    public function get(string $cep): ?CepEntity
    {
        $hasCacheResultsEnabled = config('brazilian-ceps.cache_results', true);
        $cacheResultsLifetime   = config('brazilian-ceps.cache_lifetime_in_days', 30);

        if ($hasCacheResultsEnabled) {
            return Cache::remember(
                "cep:{$cep}",
                now()->addDays($cacheResultsLifetime),
                fn() => $this->processCep($cep));
        }

        return $this->processCep($cep);
    }

    /**
     * @throws CepNotFoundException
     */
    protected function processCep(string $cep): ?CepEntity
    {
        foreach ($this->cepApis as $cepApi) {
            $cepApiProvider = new $cepApi;
            if ($cepApiProvider instanceof ConsultableCEPProvider) {
                $this->when(
                    !$this->cepEntity?->cep,
                    fn() => $this->cepEntity = $cepApiProvider->get($cep)
                );
            }
        }

        $hasNotFoundExceptionEnabled = config(
            'brazilian-ceps.throw_not_found_exception',
            false
        );

        if ($hasNotFoundExceptionEnabled) {
            $cep = MaskHelper::make($cep, '#####-###');
            throw new CepNotFoundException($cep);
        }

        return $this->cepEntity;
    }
}
