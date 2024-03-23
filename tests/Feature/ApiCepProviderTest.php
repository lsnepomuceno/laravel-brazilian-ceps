<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ApiCep;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ApiCepProviderTest extends TestCase
{
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
        $cep            = '29018-210';
        $apiCepProvider = new ApiCep();
        $apiCepProvider->get($cep);

        $originalProviderResponse = $apiCepProvider->getOriginalProviderResponse();

        $requiredFields = [
            'code',
            'state',
            'city',
            'district',
            'address'
        ];

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertArrayHasKey($field, (array) $originalProviderResponse);
        }
    }

    /**
     * @throws Exception
     */
    public function testValidatesIfTheRequestWillBeExecutedSuccessfully()
    {
        $cep            = '29018-210';
        $apiCepProvider = new ApiCep();
        $response       = $apiCepProvider->get($cep);

        if (!$response) {
            $this->markTestSkipped(
                'APICep provider unavailable.'
            );
        }

        $requiredFields = DefaultValues::successfullyRequiredFields();
        $optionalFields = DefaultValues::optionalFields();

        $this->assertIsObject($response);

        $this->assertInstanceOf(CepEntity::class, $response);

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertArrayHasKey($field, (array) $response);
        }

        foreach ($optionalFields as $field) {
            $this->isNull($field);
            $this->assertArrayHasKey($field, (array) $response);
        }
    }

    /**
     * @throws Exception
     */
    public function testValidatesWhenAnInvalidZipCepIsReceived()
    {
        $cep            = '66666666';
        $apiCepProvider = new ApiCep();
        $response       = $apiCepProvider->get($cep);

        $this->assertNull($response);
    }
}
