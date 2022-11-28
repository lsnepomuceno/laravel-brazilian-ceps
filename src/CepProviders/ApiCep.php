<?php

namespace App\Services\Cep\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\State;

class ApiCep extends BaseCepProvider
{
    protected const BASE_URL = 'https://cdn.apicep.com/file/apicep/';

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
            $data = $this->client->get("{$this->formatCep($cep, true)}.json")
                                 ->object();

            $this->originalProviderResponse = $data;

            if (!$data?->code) {
                return null;
            }

            return new CepEntity(
                city        : $data->city,
                cep         : $data->code,
                street      : $data->address,
                state       : State::get($data->state),
                uf          : $data->state,
                neighborhood: $data->district
            );
        } catch (Exception $e) {
            return null;
        }
    }
}
