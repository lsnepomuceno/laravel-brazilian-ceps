<?php

use Illuminate\Support\Facades\Route;
use LSNepomuceno\LaravelBrazilianCeps\Controllers\ConsultCepController;

Route::get('/api/consult-cep/{cep}', ConsultCepController::class)
     ->middleware('guest')
     ->name('consult-cep.api');
