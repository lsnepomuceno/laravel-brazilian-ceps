<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests;

use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\Helpers\DefaultValues;

class HttpTestCase extends TestCase
{
    protected function mockSuccessResponse(string $baseUrl): void
    {
        $mockResponse = [
            'city' => $this->faker->city(),
            'cep' => $this->faker->postcode(),
            'street' => $this->faker->streetName(),
            'uf' => $this->faker->stateAbbr(),
            'neighborhood' => $this->faker->name()
        ];

        Http::fake([
            $baseUrl => Http::response($mockResponse),
        ]);
    }

    protected function mockErrorResponse(string $baseUrl): void
    {
        Http::fake([
            '*' => Http::response(status: 500)
        ])->baseUrl($baseUrl);
    }

    protected function assertRequiredFields(?CepEntity $response): void
    {
        $requiredFields = DefaultValues::successfullyRequiredFields();

        foreach ($requiredFields as $field) {
            $this->assertNotEmpty($field);
            $this->assertArrayHasKey($field, (array)$response);
        }
    }

    protected function assertOptionalFields(?CepEntity $response): void
    {
        $optionalFields = DefaultValues::optionalFields();

        foreach ($optionalFields as $field) {
            $this->isNull($field);
            $this->assertArrayHasKey($field, (array)$response);
        }
    }
}
