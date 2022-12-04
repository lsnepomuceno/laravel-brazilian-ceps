<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Enums\States;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class StatesEnumTest extends TestCase
{
    public function testValidatesIfThe_27BrazilianStatesAreRegistered()
    {
        $stateCases = States::cases();
        $casesCount = count($stateCases ?? []);

        $this->assertEquals(27, $casesCount);
    }

    public function testValidatesIfTheAcreStateExists()
    {
        $state = States::get('AC');
        $this->assertEquals('Acre', $state);
    }

    public function testValidatesIfTheAlagoasStateExists()
    {
        $state = States::get('AL');
        $this->assertEquals('Alagoas', $state);
    }

    public function testValidatesIfTheAmapaStateExists()
    {
        $state = States::get('AP');
        $this->assertEquals('Amapá', $state);
    }

    public function testValidatesIfTheAmazonasStateExists()
    {
        $state = States::get('AM');
        $this->assertEquals('Amazonas', $state);
    }

    public function testValidatesIfTheBahiaStateExists()
    {
        $state = States::get('BA');
        $this->assertEquals('Bahia', $state);
    }

    public function testValidatesIfTheCearaStateExists()
    {
        $state = States::get('CE');
        $this->assertEquals('Ceará', $state);
    }

    public function testValidatesIfTheDistritoFederalStateExists()
    {
        $state = States::get('DF');
        $this->assertEquals('Distrito Federal', $state);
    }

    public function testValidatesIfTheEsStateExists()
    {
        $state = States::get('ES');
        $this->assertEquals('Espírito Santo', $state);
    }

    public function testValidatesIfTheGoiasStateExists()
    {
        $state = States::get('GO');
        $this->assertEquals('Goiás', $state);
    }

    public function testValidatesIfTheMaranhaoStateExists()
    {
        $state = States::get('MA');
        $this->assertEquals('Maranhão', $state);
    }

    public function testValidatesIfTheMatoGrossoStateExists()
    {
        $state = States::get('MT');
        $this->assertEquals('Mato Grosso', $state);
    }

    public function testValidatesIfTheMatoGrossoDoSulStateExists()
    {
        $state = States::get('MS');
        $this->assertEquals('Mato Grosso do Sul', $state);
    }

    public function testValidatesIfTheMinasGeraisStateExists()
    {
        $state = States::get('MG');
        $this->assertEquals('Minas Gerais', $state);
    }

    public function testValidatesIfTheParaStateExists()
    {
        $state = States::get('PA');
        $this->assertEquals('Pará', $state);
    }

    public function testValidatesIfTheParaibaStateExists()
    {
        $state = States::get('PB');
        $this->assertEquals('Paraíba', $state);
    }

    public function testValidatesIfTheParanaStateExists()
    {
        $state = States::get('PR');
        $this->assertEquals('Paraná', $state);
    }

    public function testValidatesIfThePernambucoStateExists()
    {
        $state = States::get('PE');
        $this->assertEquals('Pernambuco', $state);
    }

    public function testValidatesIfThePiauiStateExists()
    {
        $state = States::get('PI');
        $this->assertEquals('Piauí', $state);
    }

    public function testValidatesIfTheRioDeJaneiroStateExists()
    {
        $state = States::get('RJ');
        $this->assertEquals('Rio de Janeiro', $state);
    }

    public function testValidatesIfTheRioGrandeDoNorteStateExists()
    {
        $state = States::get('RN');
        $this->assertEquals('Rio Grande do Norte', $state);
    }

    public function testValidatesIfTheRioGrandeDoSulStateExists()
    {
        $state = States::get('RS');
        $this->assertEquals('Rio Grande do Sul', $state);
    }

    public function testValidatesIfTheRondoniaStateExists()
    {
        $state = States::get('RO');
        $this->assertEquals('Rondônia', $state);
    }

    public function testValidatesIfTheRoraimaStateExists()
    {
        $state = States::get('RR');
        $this->assertEquals('Roraima', $state);
    }

    public function testValidatesIfTheSantaCatarinaStateExists()
    {
        $state = States::get('SC');
        $this->assertEquals('Santa Catarina', $state);
    }

    public function testValidatesIfTheSaoPauloStateExists()
    {
        $state = States::get('SP');
        $this->assertEquals('São Paulo', $state);
    }

    public function testValidatesIfTheSergipeStateExists()
    {
        $state = States::get('SE');
        $this->assertEquals('Sergipe', $state);
    }

    public function testValidatesIfTheTocantinsStateExists()
    {
        $state = States::get('TO');
        $this->assertEquals('Tocantins', $state);
    }
}
