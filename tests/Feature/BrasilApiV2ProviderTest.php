<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ApiCep;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\BrasilApiV2;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class BrasilApiV2ProviderTest extends TestCase
{
    protected const BASE_URL = 'brasilapi.com.br/api/cep/v2/';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testValidatesCepProviderName()
    {
        $apiCepProvider = new BrasilApiV2();

        $this->assertEquals('BrasilApiV2', $apiCepProvider->getProviderName());
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructure()
    {
        #arrange
        $this->mockResponseSuccess();
        $apiCepProvider = new BrasilApiV2();

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
        $apiCepProvider = new BrasilApiV2();

        #act
        $response = $apiCepProvider->get('12345678');
        $originalProviderResponse = $apiCepProvider->getOriginalProviderResponse();

        #assert
        $this->assertNull($response);
        $this->assertNull($originalProviderResponse);
    }

    /**
     * @throws Exception
     */
    public function testValidatesWhenAnInvalidZipCepIsReceived()
    {
        $apiCepProvider = new BrasilApiV2();

        $cep = '66666666';
        $response = $apiCepProvider->get($cep);

        $this->assertNull($response);
    }

    private function mockResponseSuccess(): void
    {
        $mockResponse =  [
            "cep" => "89010025",
            "state" => "SC",
            "city" => "Blumenau",
            "neighborhood" => "Centro",
            "street" => "Rua Doutor Luiz de Freitas Melro",
            "service" => "viacep",
            "location" => [
                "type" => "Point",
                "coordinates" => [
                    "longitude" => "-49.0629788",
                    "latitude" => "-26.9244749"
                ]
            ]
        ];

        Http::fake([
            self::BASE_URL . '*' => Http::response($mockResponse, 200)
        ]);
    }

    private function mockResponseError(): void
    {
        Http::fake([
            self::BASE_URL.'*' => Http::response(null, 500)
        ]);
    }

    private function fieldsValidity($originalProviderResponse): void
    {
        $requiredFields = [
            'cep',
            'state',
            'city',
            'neighborhood',
            'street',
            'service',
            'location'
        ];

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertArrayHasKey($field, (array) $originalProviderResponse);
        }
    }
}
