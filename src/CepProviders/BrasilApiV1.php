<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use ReflectionException;

class BrasilApiV1 extends BaseCepProvider
{
    protected const BASE_URL = 'https://brasilapi.com.br/api/cep/v1/';

    /**
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->setProviderName($this::class);
        $this->client = Http::baseUrl(self::BASE_URL);
    }

    /**
     * @throws Exception
     */
    public function get(string $cep): ?CepEntity
    {
        try {
            $data = $this->client->get("{$this->formatCep($cep)}.json")
                                 ->object();

            $this->originalProviderResponse = $data;

            if (!$data?->cep) {
                return null;
            }

            return new CepEntity(
                city        : $data->city,
                cep         : $data->code,
                street      : $data->street,
                state       : States::get($data->state),
                uf          : $data->state,
                neighborhood: $data->neighborhood
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
