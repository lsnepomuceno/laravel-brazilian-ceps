<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Contracts\SearchableCEPProvider;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use ReflectionException;

class ViaCep extends BaseCepProvider implements SearchableCEPProvider
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
                city: $data->localidade,
                cep: $data->cep,
                street: $data->logradouro,
                state: States::get($data->uf),
                uf: $data->uf,
                neighborhood: $data->bairro,
                ibge: $data->ibge
            );
        } catch (Exception $e) {
            return null;
        }
    }

    public function search(string $uf, string $city, string $street): Collection
    {
        try {
            $data = $this->client
                ->get(sprintf('%s/%s/%s/json', strtoupper($uf), urlencode($city), urlencode($street)))
                ->json();

            if (empty($data) || !is_array($data) || isset($data['erro'])) {
                return collect();
            }

            return collect($data)
                ->filter(fn($item) => !empty($item['cep']))
                ->map(fn($item) => new CepEntity(
                    city: $item['localidade'],
                    cep: $item['cep'],
                    street: $item['logradouro'],
                    state: States::get($item['uf']),
                    uf: $item['uf'],
                    neighborhood: $item['bairro'],
                    complement: $item['complemento'] ?? null,
                    ibge: $item['ibge'] ?? null,
                ))
                ->values();
        } catch (Exception) {
            return collect();
        }
    }

    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }
}
