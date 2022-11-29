<?php

namespace LSNepomuceno\LaravelBrazilianCeps\CepProviders;

use Illuminate\Http\Client\PendingRequest;
use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;
use ReflectionClass;
use ReflectionException;

class BaseCepProvider
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

    /**
     * @throws ReflectionException
     */
    protected function setProviderName(string $className): void
    {
        $reflectionClass           = new ReflectionClass($className);
        $this->$this->providerName = $reflectionClass->getShortName();
    }

    public function getProviderName(): string
    {
        return $this->providerName;
    }
}
