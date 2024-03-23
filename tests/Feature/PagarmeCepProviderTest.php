<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\Pagarme;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\HttpTestCase;

class PagarmeCepProviderTest extends HttpTestCase
{
    protected Pagarme $cepProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cepProvider = new Pagarme();
    }

    public function testValidatesCepProviderName()
    {
        $cepProvider = new Pagarme();
        $this->assertEquals('Pagarme', $cepProvider->getProviderName());
    }

    private function mockResponseSuccess(): void
    {
        $mockResponse = [
            'city' => $this->faker->city(),
            'zipcode' => $this->faker->postcode(),
            'street' => $this->faker->streetName(),
            'state' => $this->faker->stateAbbr(),
            'neighborhood' => $this->faker->name()
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

        $cepProvider = new Pagarme();
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

        $cepProvider = new Pagarme();
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
        $cepProvider = new Pagarme();

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
        $cepProvider = new Pagarme();
        $response = $cepProvider->get($cep);

        $this->assertNull($response);
    }
}
