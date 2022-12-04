<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Helpers\MaskHelper;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class MaskHelperTest extends TestCase
{
    public function testValidatesMaskedValues()
    {
        $this->assertEquals(
            '(27) 3333-3333',
            MaskHelper::make('2733333333', '(##) ####-####')
        );

        $this->assertEquals(
            '+55 (27) 3333-3333',
            MaskHelper::make('2733333333', '+55 (##) ####-####')
        );

        $this->assertEquals(
            '111.111.111-11',
            MaskHelper::make('11111111111', '###.###.###-##')
        );

        $this->assertEquals(
            '99.999.999/9999-99',
            MaskHelper::make('99999999999999', '##.###.###/####-##')
        );

        $this->assertEquals(
            '6e9c6cbd-5ca2-480e-b094-e42406bd6852',
            MaskHelper::make('6e9c6cbd5ca2480eb094e42406bd6852', '########-####-####-####-############')
        );
    }
}
