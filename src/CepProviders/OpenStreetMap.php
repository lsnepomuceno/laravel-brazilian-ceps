<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Contracts\SearchableCEPProvider;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;
use ReflectionException;

class OpenStreetMap extends BaseCepProvider implements SearchableCEPProvider
{
    protected const BASE_URL = 'https://nominatim.openstreetmap.org';

    /**
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->setProviderName($this::class);
        $this->client = Http::baseUrl(self::BASE_URL)
            ->withHeaders(['User-Agent' => 'laravel-brazilian-ceps']);
    }

    /**
     * @throws Exception
     */
    public function get(string $cep): ?CepEntity
    {
        try {
            $digits = preg_replace('/\D/', '', $cep);

            $data = $this->client->get('search', [
                'postalcode'     => $digits,
                'country'        => 'BR',
                'format'         => 'json',
                'addressdetails' => 1,
                'limit'          => 1,
            ])->json();

            $this->setOriginalProviderResponse(!empty($data) ? (object) $data[0] : null);

            if (empty($data) || !isset($data[0]['address'])) {
                return null;
            }

            return $this->mapToEntity($data[0]['address']);
        } catch (Exception) {
            return null;
        }
    }

    public function search(string $uf, string $city, string $street): Collection
    {
        try {
            $data = $this->client->get('search', [
                'street'         => $street,
                'city'           => $city,
                'state'          => States::get($uf),
                'country'        => 'Brazil',
                'format'         => 'json',
                'addressdetails' => 1,
                'countrycodes'   => 'br',
            ])->json();

            if (empty($data) || !is_array($data)) {
                return collect();
            }

            return collect($data)
                ->filter(fn($item) => isset($item['address']['postcode']))
                ->map(fn($item) => $this->mapToEntity($item['address']))
                ->filter()
                ->unique('cep')
                ->values();
        } catch (Exception) {
            return collect();
        }
    }

    private function mapToEntity(array $address): ?CepEntity
    {
        $postcode = $address['postcode'] ?? null;
        if (!$postcode) {
            return null;
        }

        $uf = $this->extractUf($address);
        if (!$uf) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $postcode);
        if (strlen($digits) !== 8) {
            return null;
        }

        return new CepEntity(
            city        : $address['city'] ?? $address['town'] ?? $address['municipality'] ?? '',
            cep         : MaskHelper::make($digits, '#####-###'),
            street      : $address['road'] ?? $address['pedestrian'] ?? '',
            state       : States::get($uf),
            uf          : $uf,
            neighborhood: $address['neighbourhood'] ?? $address['suburb'] ?? $address['city_district'] ?? '',
        );
    }

    private function extractUf(array $address): ?string
    {
        // Nominatim provides ISO3166-2-lvl4 as "BR-ES"
        $isoCode = $address['ISO3166-2-lvl4'] ?? null;
        if ($isoCode && str_starts_with($isoCode, 'BR-')) {
            $uf = substr($isoCode, 3);
            if (States::get($uf) !== null) {
                return $uf;
            }
        }

        // Fallback: match by full state name
        $stateName = $address['state'] ?? null;
        if ($stateName) {
            foreach (States::cases() as $state) {
                if (mb_strtolower($state->value) === mb_strtolower($stateName)) {
                    return $state->name;
                }
            }
        }

        return null;
    }

    public function getBaseUrl(): string
    {
        return self::BASE_URL;
    }
}
