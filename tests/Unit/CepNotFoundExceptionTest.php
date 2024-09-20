<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use Illuminate\Support\Facades\Http;
use LSNepomuceno\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;

test('valida exceção CepNotFoundException', function () {
    Http::fake([
        "*" => Http::response([], 404)
    ]);

    config(['brazilian-ceps.throw_not_found_exception' => true]);

    expect(fn() => (new CepService())->get('66666666'))
        ->toThrow(CepNotFoundException::class);
});
