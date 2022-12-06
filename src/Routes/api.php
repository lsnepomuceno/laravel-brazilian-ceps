<?php

use Illuminate\Support\Facades\Route;
use LSNepomuceno\LaravelBrazilianCeps\Controllers\ConsultCepController;

$middleware = config('brazilian-ceps.api_route_middleware');

if (!is_array($middleware) || !count($middleware)) {
    $middleware = ['guest'];
}

Route::get('/api/consult-cep/{cep}', ConsultCepController::class)
     ->middleware($middleware)
     ->name('consult-cep.api');
