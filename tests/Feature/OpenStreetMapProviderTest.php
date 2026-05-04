<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\OpenStreetMap;
use LSNepomuceno\LaravelBrazilianCeps\Contracts\SearchableCEPProvider;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class OpenStreetMapProviderTest extends TestCase
{
    protected OpenStreetMap $cepProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cepProvider = new OpenStreetMap();
    }

    private function nominatimAddress(string $postcode = '29018-210', string $uf = 'ES'): array
    {
        return [
            'road'             => 'Rua da Vitória',
            'neighbourhood'    => 'Centro',
            'city'             => 'Vitória',
            'state'            => 'Espírito Santo',
            'ISO3166-2-lvl4'   => "BR-{$uf}",
            'postcode'         => $postcode,
            'country'          => 'Brazil',
            'country_code'     => 'br',
        ];
    }

    private function mockGetSuccess(string $postcode = '29018-210'): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response(
                [['address' => $this->nominatimAddress($postcode)]],
                200
            ),
        ]);
    }

    private function mockGetEmpty(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response([], 200),
        ]);
    }

    public function testValidatesCepProviderName(): void
    {
        $this->assertEquals('OpenStreetMap', $this->cepProvider->getProviderName());
    }

    public function testImplementsSearchableCepProvider(): void
    {
        $this->assertInstanceOf(SearchableCEPProvider::class, $this->cepProvider);
    }

    public function testGetReturnsCepEntity(): void
    {
        $this->mockGetSuccess();

        $provider = new OpenStreetMap();
        $response = $provider->get('29018210');

        $this->assertInstanceOf(CepEntity::class, $response);
        $this->assertEquals('29018-210', $response->cep);
        $this->assertEquals('ES', $response->uf);
        $this->assertEquals('Espírito Santo', $response->state);
    }

    public function testGetStoresOriginalProviderResponse(): void
    {
        $this->mockGetSuccess();

        $provider = new OpenStreetMap();
        $provider->get('29018210');

        $this->assertNotNull($provider->getOriginalProviderResponse());
    }

    public function testGetReturnsNullWhenNominatimReturnsEmptyArray(): void
    {
        $this->mockGetEmpty();

        $provider = new OpenStreetMap();
        $response = $provider->get('29018210');

        $this->assertNull($response);
    }

    public function testGetReturnsNullOnServerError(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response(status: 500),
        ]);

        $provider = new OpenStreetMap();
        $response = $provider->get('29018210');

        $this->assertNull($response);
    }

    public function testSearchReturnsCollectionOfCepEntities(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response([
                ['address' => $this->nominatimAddress('29018-210')],
                ['address' => $this->nominatimAddress('29018-300')],
            ], 200),
        ]);

        $provider = new OpenStreetMap();
        $results = $provider->search('ES', 'Vitória', 'Rua da Vitória');

        $this->assertCount(2, $results);
        $this->assertEquals('29018-210', $results->first()->cep);
    }

    public function testSearchDeduplicatesByCep(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response([
                ['address' => $this->nominatimAddress('29018-210')],
                ['address' => $this->nominatimAddress('29018-210')],
            ], 200),
        ]);

        $provider = new OpenStreetMap();
        $results = $provider->search('ES', 'Vitória', 'Rua da Vitória');

        $this->assertCount(1, $results);
    }

    public function testSearchReturnsEmptyCollectionWhenNominatimReturnsEmpty(): void
    {
        $this->mockGetEmpty();

        $provider = new OpenStreetMap();
        $results = $provider->search('ES', 'Vitória', 'Rua Inexistente');

        $this->assertTrue($results->isEmpty());
    }

    public function testSearchFiltersOutResultsWithoutPostcode(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response([
                ['address' => ['road' => 'Rua X', 'city' => 'Vitória']],
                ['address' => $this->nominatimAddress('29018-210')],
            ], 200),
        ]);

        $provider = new OpenStreetMap();
        $results = $provider->search('ES', 'Vitória', 'Rua');

        $this->assertCount(1, $results);
    }

    public function testGetReturnsNullForInvalidCep(): void
    {
        $cep = '66666666';
        $provider = new OpenStreetMap();
        $response = $provider->get($cep);

        $this->assertNull($response);
    }
}
