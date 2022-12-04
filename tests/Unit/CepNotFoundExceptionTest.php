<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use LSNepomuceno\LaravelBrazilianCeps\Exceptions\CepNotFoundException;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class CepNotFoundExceptionTest extends TestCase
{
    /**
     * @throws CepNotFoundException
     */
    public function testValidateCepNotFoundException()
    {
        $this->expectException(CepNotFoundException::class);
        
        config(['brazilian-ceps.throw_not_found_exception' => true]);

        $cepService = new CepService();

        $cepService->get('66666666');
    }
}
