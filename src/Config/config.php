<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache results
    |--------------------------------------------------------------------------
    |
    | This value defines whether results will be cached.
    | Default value: true
    |
    */

    'cache_results' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache lifetime in days
    |--------------------------------------------------------------------------
    |
    | This value defines how many "days" the cached results will be kept.
    | Default value: 30
    |
    */

    'cache_lifetime_in_days' => 30,

    /*
    |--------------------------------------------------------------------------
    | Throw not found exception
    |--------------------------------------------------------------------------
    |
    | This value defines whether an exception will be thrown if no results are found.
    | Default value: false
    |
    */

    'throw_not_found_exception' => false,

    /*
    |--------------------------------------------------------------------------
    | Enable api consult cep route
    |--------------------------------------------------------------------------
    |
    | This value defines whether the route "api/consult-cep/{cep}" will be enabled.
    | Default value: true
    |
    */

    'enable_api_consult_cep_route' => true,

    /*
    |--------------------------------------------------------------------------
    | Api route middleware
    |--------------------------------------------------------------------------
    |
    | This value defines which middlewares will provide access to the API route
    | Default value: ['guest']
    |
    */

    'api_route_middleware' => ['guest'],

    /*
    |--------------------------------------------------------------------------
    | Api consult cep route not found message
    |--------------------------------------------------------------------------
    |
    | This value defines the message returned if the zip code is not found in
    | the route "api/consult-cep/{cep}"
    | Default value: "CEP não encontrado."
    |
    */

    'not_found_message' => 'CEP não encontrado.'
];
