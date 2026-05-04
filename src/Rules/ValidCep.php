<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;
use Throwable;

class ValidCep implements ValidationRule
{
    private bool $checkExistence = false;

    public function mustExist(): static
    {
        $this->checkExistence = true;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if (strlen($digits) !== 8) {
            $fail('O CEP informado possui um formato inválido.');

            return;
        }

        if ($this->checkExistence) {
            try {
                $cep = app(CepService::class)->get($digits);

                if ($cep === null) {
                    $fail('O CEP informado não foi encontrado.');
                }
            } catch (Throwable) {
                $fail('O CEP informado não foi encontrado.');
            }
        }
    }
}
