<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;

test(
    'the expected result should be the same after applying the mask to the values',
    function ($input, $mask, $expected) {
        $masked = MaskHelper::make($input, $mask);

        expect($masked)->toBe($expected);
    }
)->with([
    'simple phone' => [
        '2733333333',
        '(##) ####-####',
        '(27) 3333-3333'
    ],
    'international phone' => [
        '2733333333',
        '+55 (##) ####-####',
        '+55 (27) 3333-3333'
    ],
    'CPF' => [
        '11111111111',
        '###.###.###-##',
        '111.111.111-11'
    ],
    'CNPJ' => [
        '99999999999999',
        '##.###.###/####-##',
        '99.999.999/9999-99'
    ],
    'UUID' => [
        '6e9c6cbd5ca2480eb094e42406bd6852',
        '########-####-####-####-############',
        '6e9c6cbd-5ca2-480e-b094-e42406bd6852'
    ],
]);
