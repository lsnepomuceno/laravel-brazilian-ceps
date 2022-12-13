<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Exception;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use LSNepomuceno\LaravelBrazilianCeps\Facades\CEP;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class CepFacadeTest extends TestCase
{
    public function testCepFacadeReturnsCorrectResponseStructure()
    {
        $response = CEP::get('29018-210');

        $this->assertInstanceOf(CepEntity::class, $response);
    }

    public function testCepFacadeThrowsExceptionWhenCepNotFound()
    {
        config(['brazilian-ceps.throw_not_found_exception' => true]);

        $this->expectException(Exception::class);

        CEP::get('66666666');
    }
}
