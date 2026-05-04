<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ViaCep;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ViaCepSearchProviderTest extends TestCase
{
    protected ViaCep $cepProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cepProvider = new ViaCep();
    }

    private function mockSearchSuccess(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response([
                [
                    'cep'        => '29018-210',
                    'logradouro' => 'Rua da Vitória',
                    'complemento'=> '',
                    'bairro'     => 'Centro',
                    'localidade' => 'Vitória',
                    'uf'         => 'ES',
                    'ibge'       => '3205309',
                ],
                [
                    'cep'        => '29018-300',
                    'logradouro' => 'Rua Sete de Setembro',
                    'complemento'=> '',
                    'bairro'     => 'Centro',
                    'localidade' => 'Vitória',
                    'uf'         => 'ES',
                    'ibge'       => '3205309',
                ],
            ], 200),
        ]);
    }

    private function mockSearchEmpty(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response([], 200),
        ]);
    }

    private function mockSearchError(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response(['erro' => 'true'], 200),
        ]);
    }

    public function testSearchReturnsCollectionOfCepEntities(): void
    {
        $this->mockSearchSuccess();

        $provider = new ViaCep();
        $results = $provider->search('ES', 'Vitória', 'Vitória');

        $this->assertCount(2, $results);
        $this->assertEquals('29018-210', $results->first()->cep);
        $this->assertEquals('ES', $results->first()->uf);
    }

    public function testSearchReturnsEmptyCollectionWhenNoResults(): void
    {
        $this->mockSearchEmpty();

        $provider = new ViaCep();
        $results = $provider->search('ES', 'Vitória', 'Inexistente');

        $this->assertTrue($results->isEmpty());
    }

    public function testSearchReturnsEmptyCollectionOnApiError(): void
    {
        $this->mockSearchError();

        $provider = new ViaCep();
        $results = $provider->search('ES', 'Vitória', 'Inexistente');

        $this->assertTrue($results->isEmpty());
    }

    public function testSearchReturnsEmptyCollectionOnServerError(): void
    {
        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response(status: 500),
        ]);

        $provider = new ViaCep();
        $results = $provider->search('ES', 'Vitória', 'Vitória');

        $this->assertTrue($results->isEmpty());
    }
}
