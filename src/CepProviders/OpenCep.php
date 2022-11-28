<?php

namespace App\Services\Cep\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\State;

class OpenCep extends BaseCepProvider
{
    protected const BASE_URL = 'https://opencep.com/v1/';

    public function __construct()
    {
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
                city        : $data->localidade,
                cep         : $data->cep,
                street      : $data->logradouro,
                state       : State::get($data->uf),
                uf          : $data->uf,
                neighborhood: $data->bairro
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
