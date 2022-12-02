<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Controllers;

use Illuminate\Http\JsonResponse;
use LSNepomuceno\LaravelBrazilianCeps\Resources\CepResource;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;

class ConsultCepController extends Controller
{
    public function __construct(protected CepService $cepService)
    {
    }

    public function __invoke(string|int $cep): JsonResponse|CepResource
    {
        $cep                = $this->cepService->get($cep);
        $cepNotFoundMessage = config(
            'brazilian-ceps.api_consult_cep_route_not_found_message',
            'CEP não encontrado.'
        );

        return $cep?->cep
            ? CepResource::make($cep)
            : response()->json(['message' => $cepNotFoundMessage], 404);
    }
}
