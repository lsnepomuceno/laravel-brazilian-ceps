<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Illuminate\Http\Client\PendingRequest;
use LSNepomuceno\LaravelBrazilianCeps\Contracts\ConsultableCEPProvider;
use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;
use ReflectionClass;
use ReflectionException;

abstract class BaseCepProvider implements ConsultableCEPProvider
{
    protected PendingRequest $client;
    protected string         $providerName;
    protected ?object        $originalProviderResponse;

    protected function formatCep(string $cep, bool $isFormattedReturn = false): string
    {
        $cep = preg_replace('/\D/', '', $cep);

        return $isFormattedReturn
            ? MaskHelper::make($cep, '#####-###')
            : $cep;
    }

    protected function setOriginalProviderResponse(array|object|null $data): void
    {
        $this->originalProviderResponse = !empty($data) ? $data : null;
    }

    public function getOriginalProviderResponse(): ?object
    {
        return $this->originalProviderResponse;
    }

    /**
     * @throws ReflectionException
     */
    protected function setProviderName(string $className): void
    {
        $reflectionClass    = new ReflectionClass($className);
        $this->providerName = $reflectionClass->getShortName();
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }
}
