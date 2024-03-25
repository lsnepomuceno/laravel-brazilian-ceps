<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\Postomon;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\HttpTestCase;

class PostomonCepProviderTest extends HttpTestCase
{
    protected Postomon $cepProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cepProvider = new Postomon();
    }

    public function testValidatesCepProviderName()
    {
        $cepProvider = new Postomon();
        $this->assertEquals('Postomon', $cepProvider->getProviderName());
    }

    private function mockResponseSuccess(): void
    {
        $mockResponse = [
            'cidade' => $this->faker->city(),
            'cep' => $this->faker->postcode(),
            'logradouro' => $this->faker->streetName(),
            'estado' => $this->faker->stateAbbr(),
            'bairro' => $this->faker->name(),
            'cidade_info' => [
               'codigo_ibge' => $this->faker->numerify('######')
            ]
        ];

        Http::fake([
            "{$this->cepProvider->getBaseUrl()}*" => Http::response($mockResponse, 200)
        ]);
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructure()
    {
        $this->mockResponseSuccess();

        $cepProvider = new Postomon();
        $response = $cepProvider->get($this->faker->postcode());
        $originalProviderResponse = $cepProvider->getOriginalProviderResponse();
        $this->assertInstanceOf(CepEntity::class, $response);
        $this->assertNotEmpty($originalProviderResponse);
    }

    /**
     * @throws Exception
     */
    public function testValidatesIfTheRequestWillBeExecutedSuccessfully()
    {
        $this->mockResponseSuccess();

        $cepProvider = new Postomon();
        $response = $cepProvider->get($this->faker->postcode());

        $this->assertIsObject($response);
        $this->assertInstanceOf(CepEntity::class, $response);
        $this->assertRequiredFields($response);
        $this->assertOptionalFields($response);
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructureError()
    {
        $this->mockErrorResponse($this->cepProvider->getBaseUrl());
        $cepProvider = new Postomon();

        $response = $cepProvider->get('12345678');
        $originalProviderResponse = $cepProvider->getOriginalProviderResponse();

        $this->assertNull($response);
        $this->assertNull($originalProviderResponse);
    }

    /**
     * @throws Exception
     */
    public function testValidatesWhenAnInvalidZipCepIsReceived()
    {
        $cep = '66666666';
        $cepProvider = new Postomon();
        $response = $cepProvider->get($cep);

        $this->assertNull($response);
    }
}
