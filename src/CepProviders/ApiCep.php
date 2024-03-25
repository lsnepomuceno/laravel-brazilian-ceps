<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use ReflectionException;

class ApiCep extends BaseCepProvider
{
    protected const BASE_URL = 'https://cdn.apicep.com/file/apicep/';

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
            $data = $this->client->get("{$this->formatCep($cep, true)}.json")
                                 ->object();

            $this->setOriginalProviderResponse($data);

            if (!$data?->code) {
                return null;
            }

            return new CepEntity(
                city        : $data->city,
                cep         : $data->code,
                street      : $data->address,
                state       : States::get($data->state),
                uf          : $data->state,
                neighborhood: $data->district
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
