<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Support\Facades\Validator;
use LSNepomuceno\LaravelBrazilianCeps\Rules\ValidCep;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ValidCepRuleFeatureTest extends TestCase
{
    public function testValidCepPassesValidation(): void
    {
        $validator = Validator::make(
            ['cep' => '29018-210'],
            ['cep' => [new ValidCep]]
        );

        $this->assertTrue($validator->passes());
    }

    public function testValidCepWithoutMaskPassesValidation(): void
    {
        $validator = Validator::make(
            ['cep' => '29018210'],
            ['cep' => [new ValidCep]]
        );

        $this->assertTrue($validator->passes());
    }

    public function testInvalidCepFailsValidation(): void
    {
        $validator = Validator::make(
            ['cep' => '123'],
            ['cep' => [new ValidCep]]
        );

        $this->assertTrue($validator->fails());
    }

    public function testValidationErrorMessageIsReturned(): void
    {
        $validator = Validator::make(
            ['cep' => 'not-a-cep'],
            ['cep' => [new ValidCep]]
        );

        $this->assertArrayHasKey('cep', $validator->errors()->toArray());
    }

    public function testValidCepWithMustExistPassesForRealCep(): void
    {
        $validator = Validator::make(
            ['cep' => '29018-210'],
            ['cep' => [(new ValidCep)->mustExist()]]
        );

        $this->assertTrue($validator->passes());
    }

    public function testNonExistentCepWithMustExistFailsValidation(): void
    {
        $validator = Validator::make(
            ['cep' => '00000-000'],
            ['cep' => [(new ValidCep)->mustExist()]]
        );

        $this->assertTrue($validator->fails());
    }

    public function testRuleWorksAlongsideOtherRules(): void
    {
        $validator = Validator::make(
            ['cep' => ''],
            ['cep' => ['required', new ValidCep]]
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cep', $validator->errors()->toArray());
    }
}
