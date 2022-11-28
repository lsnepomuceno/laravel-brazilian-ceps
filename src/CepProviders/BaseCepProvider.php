<?php

namespace App\Services\Cep\Providers;

use Illuminate\Http\Client\PendingRequest;
use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;

class BaseCepProvider
{
    protected PendingRequest $client;
    protected ?object        $originalProviderResponse;

    protected function formatCep(string $cep, bool $isFormattedReturn = false): string
    {
        $cep = preg_replace('/\D/', '', $cep);

        return $isFormattedReturn
            ? MaskHelper::make($cep, '#####-###')
            : $cep;
    }
}
