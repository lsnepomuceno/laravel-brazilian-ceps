<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV1;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\HttpTestCase;

class BrasilApiV1ProviderTest extends HttpTestCase
{
    protected BrasilApiV1 $cepProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cepProvider = new BrasilApiV1();
    }

    public function testValidatesCepProviderName()
    {
        $cepProvider = new BrasilApiV1();
        $this->assertEquals('BrasilApiV1', $cepProvider->getProviderName());
    }

    private function mockResponseSuccess(): void
    {
        $mockResponse = [
            'city' => $this->faker->city(),
            'cep' => $this->faker->postcode(),
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

        $cepProvider = new BrasilApiV1();
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

        $cepProvider = new BrasilApiV1();
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
        $cepProvider = new BrasilApiV1();

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
        $cepProvider = new BrasilApiV1();
        $response = $cepProvider->get($cep);

        $this->assertNull($response);
    }
}
