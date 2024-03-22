<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ApiCep;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ApiCepProviderTest extends TestCase
{
    private const BASE_URL = 'cdn.apicep.com/file/apicep/';

    public function testValidatesCepProviderName()
    {
        $apiCepProvider = new ApiCep();
        $this->assertEquals('ApiCep', $apiCepProvider->getProviderName());
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructure()
    {
        #arrange
        $this->mockResponseSuccess();

        #act
        $apiCep = new ApiCep();
        $response = $apiCep->get('12345678');
        $originalProviderResponse = $apiCep->getOriginalProviderResponse();

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

        #act
        $apiCep = new ApiCep();
        $response = $apiCep->get('12345678');
        $originalProviderResponse = $apiCep->getOriginalProviderResponse();

        #assert
        $this->assertNull($response);
        $this->assertNull($originalProviderResponse);
    }

    private function mockResponseSuccess(): void
    {
        $mockResponse = [
            "code" => "12345678",
            "state" => "SP",
            "city" => "Sao Paulo",
            "district" => "Vila Mariana",
            "address" => "R. Morgado de Mateus",
        ];

        Http::fake([
            self::BASE_URL . '*.json' => Http::response($mockResponse, 200)
        ]);
    }

    private function mockResponseError(): void
    {
        Http::fake([
            self::BASE_URL.'*.json' => Http::response(null, 500)
        ]);
    }

    private function fieldsValidity($originalProviderResponse): void
    {
        $requiredFields = ['code', 'state', 'city', 'district', 'address'];

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
        $cep = '66666666';
        $apiCepProvider = new ApiCep();
        $response = $apiCepProvider->get($cep);

        $this->assertNull($response);
    }
}
