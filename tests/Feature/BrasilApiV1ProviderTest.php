<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV1;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class BrasilApiV1ProviderTest extends TestCase
{
    private const BASE_URL = 'brasilapi.com.br/api/cep/v1/';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testValidatesCepProviderName()
    {
        $apiCepProvider = new BrasilApiV1();

        $this->assertEquals('BrasilApiV1', $apiCepProvider->getProviderName());
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructure()
    {
        #arrange
        $this->mockResponseSuccess();
        $apiCepProvider = new BrasilApiV1();


        #act
        $response = $apiCepProvider->get('12345678');

        $originalProviderResponse = $apiCepProvider->getOriginalProviderResponse();
        #assert
        $this->assertInstanceOf(CepEntity::class, $response);
        $this->assertNotEmpty($originalProviderResponse);
        $this->fieldsValidity($originalProviderResponse);
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructureError()
    {
        #arrange
        $this->mockResponseError();
        $apiCepProvider = new BrasilApiV1();

        #act
        $response = $apiCepProvider->get('12345678');
        $originalProviderResponse = $apiCepProvider->getOriginalProviderResponse();

        #assert
        $this->assertNull($response);
        $this->assertNull($originalProviderResponse);
    }

    private function mockResponseSuccess(): void
    {
        $mockResponse = [
            "city" => "Sao Paulo",
            "cep" => "12345678",
            "street" => "R. Morgado de Mateus",
            "state" => "SP",
            "neighborhood" => "Vila Mariana",
        ];

        Http::fake([
            self::BASE_URL . '*' => Http::response($mockResponse, 200)
        ]);
    }

    private function mockResponseError(): void
    {
        Http::fake([
            self::BASE_URL . '*' => Http::response(null, 500)
        ]);
    }

    private function fieldsValidity($originalProviderResponse): void
    {
        $requiredFields = ['cep', 'state', 'city', 'neighborhood', 'street'];

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertArrayHasKey($field, (array) $originalProviderResponse);
        }
    }

    /**
     * @throws Exception
     */
    public function testValidatesWhenAnInvalidZipCepIsReceived()
    {
        $apiCepProvider = new BrasilApiV1();

        $cep = '66666666';
        $response = $apiCepProvider->get($cep);

        $this->assertNull($response);
    }
}
