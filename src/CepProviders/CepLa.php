<?php

namespace App\Services\Cep\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\State;

class CepLa extends BaseCepProvider
{
    protected const BASE_URL = 'http://cep.la/';

    public function __construct()
    {
        $this->client = Http::accept('application/json')->baseUrl(self::BASE_URL);
    }

    /**
     * @throws Exception
     */
    public function get(string $cep): ?CepEntity
    {
        try {
            $data = $this->client->get($this->formatCep($cep))
                                 ->object();

            $this->originalProviderResponse = $data;

            if (!$data?->cep) {
                return null;
            }

            return new CepEntity(
                city        : $data->city,
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
