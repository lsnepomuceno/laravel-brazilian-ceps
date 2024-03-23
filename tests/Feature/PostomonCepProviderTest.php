<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\Postomon;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class PostomonCepProviderTest extends TestCase
{
    public function testValidatesCepProviderName()
    {
        $apiCepProvider = new Postomon();
        $this->assertEquals('Postomon', $apiCepProvider->getProviderName());
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructure()
    {
        $cep            = '29018-210';
        $apiCepProvider = new Postomon();
        $apiCepProvider->get($cep);

        $originalProviderResponse = $apiCepProvider->getOriginalProviderResponse();

        $requiredFields = [
            'cep',
            'estado',
            'cidade',
            'bairro',
            'logradouro'
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
        $apiCepProvider = new Postomon();
        $response       = $apiCepProvider->get($cep);

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
        $apiCepProvider = new Postomon();
        $response       = $apiCepProvider->get($cep);

        $this->assertNull($response);
    }
}
