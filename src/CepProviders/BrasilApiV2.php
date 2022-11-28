<?php

namespace App\Services\Cep\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\State;

class BrasilApiV2 extends BaseCepProvider
{
    protected const BASE_URL = 'https://brasilapi.com.br/api/cep/v2/';

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
                city        : $data->city,
                cep         : $data->code,
                street      : $data->street,
                state       : State::get($data->state),
                uf          : $data->state,
                neighborhood: $data->neighborhood
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
