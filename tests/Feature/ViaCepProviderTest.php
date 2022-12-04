<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use LSNepomuceno\LaravelBrazilianCeps\CepProviders\ViaCep;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ViaCepProviderTest extends TestCase
{
    public function testValidatesCepProviderName()
    {
        $apiCepProvider = new ViaCep();
        $this->assertEquals('ViaCep', $apiCepProvider->getProviderName());
    }

    /**
     * @throws Exception
     */
    public function testValidatesOriginalResponseStructure()
    {
        $cep            = '29018-210';
        $apiCepProvider = new ViaCep();
        $apiCepProvider->get($cep);

        $originalProviderResponse = $apiCepProvider->getOriginalProviderResponse();

        $requiredFields = [
            'cep',
            'uf',
            'localidade',
            'bairro',
            'logradouro'
        ];

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertObjectHasAttribute($field, $originalProviderResponse);
        }
    }

    /**
     * @throws Exception
     * @depends testValidatesOriginalResponseStructure
     */
    public function testValidatesIfTheRequestWillBeExecutedSuccessfully()
    {
        $cep            = '29018-210';
        $apiCepProvider = new ViaCep();
        $response       = $apiCepProvider->get($cep);

        $requiredFields = DefaultValues::successfullyRequiredFields();
        $optionalFields = DefaultValues::optionalFields();

        $this->assertIsObject($response);

        $this->assertInstanceOf(CepEntity::class, $response);

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertObjectHasAttribute($field, $response);
        }

        foreach ($optionalFields as $field) {
            $this->isNull($field);
            $this->assertObjectHasAttribute($field, $response);
        }
    }

    /**
     * @throws Exception
     */
    public function testValidatesWhenAnInvalidZipCepIsReceived()
    {
        $cep            = '66666666';
        $apiCepProvider = new ViaCep();
        $response       = $apiCepProvider->get($cep);

        $this->assertNull($response);
    }
}
