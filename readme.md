<h1 align="center">Search addresses easily with Laravel Brazilian CEPs</h1>

<p align="center">
  <a href="https://github.com/lsnepomuceno/laravel-brazilian-ceps/releases/latest">
    <img src="https://poser.pugx.org/lsnepomuceno/laravel-brazilian-ceps/v" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/lsnepomuceno/laravel-brazilian-ceps/stats">
    <img src="https://poser.pugx.org/lsnepomuceno/laravel-brazilian-ceps/downloads" alt="Total Downloads">
  </a>
  <a href="https://github.com/lsnepomuceno/laravel-brazilian-ceps/tree/dev">
    <img src="https://poser.pugx.org/lsnepomuceno/laravel-brazilian-ceps/v/unstable" alt="Latest Unstable Version">
  </a>
  <a href="https://github.com/lsnepomuceno/laravel-brazilian-ceps/blob/main/LICENSE.md">
    <img src="https://poser.pugx.org/lsnepomuceno/laravel-brazilian-ceps/license" alt="License">
  </a>
  <a href="https://github.com/lsnepomuceno/laravel-brazilian-ceps/actions/workflows/main_action.yml">
    <img src="https://github.com/lsnepomuceno/laravel-brazilian-ceps/actions/workflows/action_laravel_11.yml/badge.svg?branch=main" alt="Tests">
  </a>
</p>

# Minimum requirements
* PHP: ^8.1, ^8.2 or ^8.3
* Laravel: 9, 10 or 11
* PHP Extensions: fileinfo, mbstring, json

# Install
Require this package in your composer.json and update composer. This will download the package and the dependencies libraries also.

```Shell
composer require lsnepomuceno/laravel-brazilian-ceps
```

Export the settings file using the command below
```Shell
php artisan vendor:publish --tag=brazilian-ceps
```


# Usage

## Using CepService:
```PHP
<?php

use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;

class ExampleController() {
    // PHP 8: Constructor property promotion
    public function __construct(protected CepService $cepService) { }
    
    
    public function dummyFunction(string|int $cep){
       $address = $this->cepService->get($cep);
       
       dd($address);
    }
}

```

### The returned value will have the structure below, see [CepEntity](https://github.com/lsnepomuceno/laravel-brazilian-ceps/blob/main/src/Entities/CepEntity.php):

```PHP
 LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity {
    city: string,
    cep: string,
    street: string,
    state: string,
    uf: string,
    neighborhood: string,
    number: string | int | null,
    complement: string | null,
  }

```
#### :exclamation: By default, if the CEP is not found, the returned value will be *null*. If you need exception handling, the option can be enabled in the configuration file.


```PHP
// config/brazilian-ceps.php

<?php
  
  'throw_not_found_exception' => true
  
```

#### :exclamation: After setting the value of the "throw_not_found_exception" variable to true, remember to update your code:

```PHP
<?php

use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;
use LSNepomuceno\LaravelBrazilianCeps\Exceptions\CepNotFoundException;

class ExampleController() {
    // PHP 8: Constructor property promotion
    public function __construct(protected CepService $cepService) { }
    
    
    public function dummyFunction(string|int $cep){
       try {
         $address = $this->cepService->get($cep);

         dd($address);
       } catch(CepNotFoundException $e) {
          // TODO necessary
       }
    }
}

```

<hr>

## Route API
##### By default, the package will provide an API route for looking up addresses, as specified below.

<table>
  <thead>
    <tr>
      <th>Verb</th>
      <th>URI</th>
      <th>Invokable Controller</th>
      <th>Route Name</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>GET</td>
      <td>api/consult-cep/{cep}</td>
      <td>LSNepomuceno\LaravelBrazilianCeps\Controllers\ConsultCepController</td>
      <td>consult-cep.api</td>
    </tr>
  </tbody>
</table>

#### :exclamation: In some cases it may be necessary to deactivate this route, in which case just change the value of the "enable_api_consult_cep_route" configuration variable to false, as example below:

```PHP
// config/brazilian-ceps.php

<?php
  
  'enable_api_consult_cep_route' => false
  
```

#### :exclamation: You can also change the message if the CEP is not found:

```PHP
// config/brazilian-ceps.php

<?php
  
  'not_found_message' => 'Type here the message you want.'
  
```

#### :exclamation: The initial middleware of the route is "guest", if it is necessary to modify it, just adjust the configuration file:

```PHP
// config/brazilian-ceps.php

<?php
  
  'api_route_middleware' => ['guest']
  
```

<hr>

## Cache Results

#### By default, the results cache are cached and have a lifetime of 30 days, if you need to disable or change the lifetime, just update the configuration variables, as described below.

```PHP
// config/brazilian-ceps.php

<?php
  
  'cache_results' => true,
  
  'cache_lifetime_in_days' => 30
  
```

## Tests

#### To ensure the delivery of data, several public providers are used, with this, the need to standardize and apply tests for better code quality was seen. About 70+ tests are included in the package.

#### Tests can be verified through the badge [![tests badge](https://github.com/lsnepomuceno/laravel-brazilian-ceps/actions/workflows/action_laravel_11.yml/badge.svg?branch=main)](https://github.com/lsnepomuceno/laravel-brazilian-ceps/actions/workflows/main_action.yml)


## License
The MIT License (MIT). Please see [License File](/LICENSE.md) for more information.
