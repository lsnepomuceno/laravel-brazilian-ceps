<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use ReflectionException;

class ViaCep extends BaseCepProvider
{
    protected const BASE_URL = 'https://viacep.com.br/ws';

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
            $data = $this->client->get("{$this->formatCep($cep)}/json")
                                 ->object();

            $this->setOriginalProviderResponse($data);

            if (!$data?->cep) {
                return null;
            }

            return new CepEntity(
                city        : $data->localidade,
                cep         : $data->cep,
                street      : $data->logradouro,
                state       : States::get($data->uf),
                uf          : $data->uf,
                neighborhood: $data->bairro
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
