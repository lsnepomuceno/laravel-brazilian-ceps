<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Feature;

use Illuminate\Support\Facades\Event;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Events\CepFound;
use LSNepomuceno\LaravelBrazilianCeps\Events\CepNotFound;
use LSNepomuceno\LaravelBrazilianCeps\Events\CepQueried;
use LSNepomuceno\LaravelBrazilianCeps\Facades\CEP;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class CepEventsTest extends TestCase
{
    public function testCepQueriedIsDispatchedOnEveryLookup(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        CEP::get('29018-210');

        Event::assertDispatched(CepQueried::class, function (CepQueried $event) {
            return $event->cep === '29018-210';
        });
    }

    public function testCepFoundIsDispatchedWhenCepExists(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        CEP::get('29018-210');

        Event::assertDispatched(CepFound::class, function (CepFound $event) {
            return $event->cep === '29018-210'
                && $event->entity instanceof CepEntity;
        });
    }

    public function testCepNotFoundIsDispatchedWhenCepDoesNotExist(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        CEP::get('00000000');

        Event::assertDispatched(CepNotFound::class, function (CepNotFound $event) {
            return $event->cep === '00000000';
        });
    }

    public function testCepFoundIsNotDispatchedWhenCepDoesNotExist(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        CEP::get('00000000');

        Event::assertNotDispatched(CepFound::class);
    }

    public function testCepNotFoundIsNotDispatchedWhenCepExists(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        CEP::get('29018-210');

        Event::assertNotDispatched(CepNotFound::class);
    }

    public function testCepQueriedCarriesTheRequestedCep(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        $cep = '29018-210';
        CEP::get($cep);

        Event::assertDispatched(CepQueried::class, fn (CepQueried $e) => $e->cep === $cep);
    }

    public function testCepFoundCarriesEntityWithCorrectUf(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        CEP::get('29018-210');

        Event::assertDispatched(CepFound::class, fn (CepFound $e) => $e->entity->uf === 'ES');
    }

    public function testEventsAreDispatchedWhenUsingServiceDirectly(): void
    {
        Event::fake([CepQueried::class, CepFound::class, CepNotFound::class]);

        app(CepService::class)->get('29018-210');

        Event::assertDispatched(CepQueried::class);
        Event::assertDispatched(CepFound::class);
        Event::assertNotDispatched(CepNotFound::class);
    }
}
