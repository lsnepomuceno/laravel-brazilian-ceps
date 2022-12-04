<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class CepEntityTest extends TestCase
{
    public function testValidatesCepEntityStructure()
    {
        $cepEntity = new CepEntity(
            city        : 'city',
            cep         : 'cep',
            street      : 'street',
            state       : 'state',
            uf          : 'uf',
            neighborhood: 'neighborhood',
            number      : 'number',
            complement  : 'complement'
        );

        $this->assertIsArray($cepEntity->toArray());
    }

    public function testValidatesCepEntityStructureWithIntegerNumber()
    {
        $cepEntity = new CepEntity(
            city        : 'city',
            cep         : 'cep',
            street      : 'street',
            state       : 'state',
            uf          : 'uf',
            neighborhood: 'neighborhood',
            number      : 1000,
            complement  : 'complement'
        );

        $this->assertIsArray($cepEntity->toArray());
    }

    public function testValidatesCepEntityStructureWhenComplementAndNumberAreNull()
    {
        $cepEntity = new CepEntity(
            city        : 'city',
            cep         : 'cep',
            street      : 'street',
            state       : 'state',
            uf          : 'uf',
            neighborhood: 'neighborhood',
            number      : null,
            complement  : null
        );

        $this->assertIsArray($cepEntity->toArray());
    }
}
