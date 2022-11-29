<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Exceptions;

use Exception;
use Stringable;

class CepNotFoundException extends Exception implements Stringable
{
    public function __construct(string|int $cep, int $code = 0, Exception $previous = null)
    {
        $message = "We were unable to locate the CEP: \"{$cep}\" on any of the available providers.";
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
