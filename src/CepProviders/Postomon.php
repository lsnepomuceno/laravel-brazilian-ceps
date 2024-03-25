<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use ReflectionException;

class Postomon extends BaseCepProvider
{
    protected const BASE_URL = 'https://api.postmon.com.br/v1/cep/';

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
            $data = $this->client->get($this->formatCep($cep))->object();

            $this->setOriginalProviderResponse($data);

            if (!$data?->cep) {
                return null;
            }

            return new CepEntity(
                city        : $data->cidade,
                cep         : $data->cep,
                street      : $data->logradouro,
                state       : States::get($data->estado),
                uf          : $data->estado,
                neighborhood: $data->bairro,
                ibge        : $data->cidade_info?->codigo_ibge
            );
        } catch (Exception $e) {
            return null;
        }
    }

    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }
}
