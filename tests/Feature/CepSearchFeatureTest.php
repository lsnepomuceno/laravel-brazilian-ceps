<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Support\Collection;
use LSNepomuceno\LaravelBrazilianCeps\Facades\CEP;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class CepSearchFeatureTest extends TestCase
{
    public function testSearchViaFacadeReturnsCollection(): void
    {
        $results = CEP::search('ES', 'Vitória', 'Vitória');

        $this->assertInstanceOf(Collection::class, $results);
    }

    public function testSearchReturnsCollectionOfCepEntities(): void
    {
        $results = CEP::search('SP', 'São Paulo', 'Paulista');

        $this->assertInstanceOf(Collection::class, $results);

        if ($results->isNotEmpty()) {
            $this->assertInstanceOf(CepEntity::class, $results->first());
            $this->assertEquals('SP', $results->first()->uf);
        }
    }

    public function testSearchWithFakeAddressReturnsEmptyCollection(): void
    {
        $results = CEP::search('ES', 'CidadeQueNaoExisteXYZ', 'RuaQueNaoExisteXYZ');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertTrue($results->isEmpty());
    }

    public function testSearchResultsAreDeduplicatedByCep(): void
    {
        $results = CEP::search('SP', 'São Paulo', 'Paulista');

        if ($results->count() > 1) {
            $uniqueCeps = $results->pluck('cep')->unique()->count();
            $this->assertEquals($uniqueCeps, $results->count());
        }

        $this->assertInstanceOf(Collection::class, $results);
    }

    public function testSearchReturnsCepEntitiesWithRequiredFields(): void
    {
        $results = CEP::search('ES', 'Vitória', 'Vitória');

        $results->each(function (CepEntity $entity) {
            $this->assertNotEmpty($entity->cep);
            $this->assertNotEmpty($entity->uf);
            $this->assertNotEmpty($entity->city);
        });
    }
}
