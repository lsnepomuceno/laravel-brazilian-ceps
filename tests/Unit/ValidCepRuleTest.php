<?php

namespace LSNepomuceno\LaravelBrazilianCeps\Tests\Unit;

use Exception;
use LSNepomuceno\LaravelBrazilianCeps\Entities\CepEntity;
use LSNepomuceno\LaravelBrazilianCeps\Rules\ValidCep;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;
use LSNepomuceno\LaravelBrazilianCeps\Tests\TestCase;

class ValidCepRuleTest extends TestCase
{
    private function getFailures(ValidCep $rule, string $value): array
    {
        $failures = [];
        $rule->validate('cep', $value, function (string $message) use (&$failures) {
            $failures[] = $message;
        });

        return $failures;
    }

    public function testValidCepDigitsOnlyPasses(): void
    {
        $this->assertEmpty($this->getFailures(new ValidCep, '29018210'));
    }

    public function testValidCepWithMaskPasses(): void
    {
        $this->assertEmpty($this->getFailures(new ValidCep, '29018-210'));
    }

    public function testCepWithTooFewDigitsFails(): void
    {
        $this->assertNotEmpty($this->getFailures(new ValidCep, '2901821'));
    }

    public function testCepWithTooManyDigitsFails(): void
    {
        $this->assertNotEmpty($this->getFailures(new ValidCep, '290182100'));
    }

    public function testCepWithNonNumericCharsFails(): void
    {
        $this->assertNotEmpty($this->getFailures(new ValidCep, 'ABCDE-FGH'));
    }

    public function testCepEmptyStringFails(): void
    {
        $this->assertNotEmpty($this->getFailures(new ValidCep, ''));
    }

    public function testInvalidFormatMessageIsInPortuguese(): void
    {
        $failures = $this->getFailures(new ValidCep, '123');

        $this->assertStringContainsString('formato inválido', $failures[0]);
    }

    public function testMustExistReturnsSameInstance(): void
    {
        $rule = new ValidCep;

        $this->assertSame($rule, $rule->mustExist());
    }

    public function testMustExistPassesWhenServiceReturnsEntity(): void
    {
        $entity = new CepEntity(
            city        : 'Vitória',
            cep         : '29018-210',
            street      : 'Rua da Vitória',
            state       : 'Espírito Santo',
            uf          : 'ES',
            neighborhood: 'Centro',
        );

        $mock = $this->createMock(CepService::class);
        $mock->method('get')->willReturn($entity);
        $this->app->bind(CepService::class, fn () => $mock);

        $this->assertEmpty($this->getFailures((new ValidCep)->mustExist(), '29018-210'));
    }

    public function testMustExistFailsWhenServiceReturnsNull(): void
    {
        $mock = $this->createMock(CepService::class);
        $mock->method('get')->willReturn(null);
        $this->app->bind(CepService::class, fn () => $mock);

        $failures = $this->getFailures((new ValidCep)->mustExist(), '29018-210');

        $this->assertNotEmpty($failures);
        $this->assertStringContainsString('não foi encontrado', $failures[0]);
    }

    public function testMustExistFailsWhenServiceThrowsException(): void
    {
        $mock = $this->createMock(CepService::class);
        $mock->method('get')->willThrowException(new Exception('Service unavailable'));
        $this->app->bind(CepService::class, fn () => $mock);

        $failures = $this->getFailures((new ValidCep)->mustExist(), '29018-210');

        $this->assertNotEmpty($failures);
        $this->assertStringContainsString('não foi encontrado', $failures[0]);
    }

    public function testFormatValidationRunsBeforeExistenceCheck(): void
    {
        $mock = $this->createMock(CepService::class);
        $mock->expects($this->never())->method('get');
        $this->app->bind(CepService::class, fn () => $mock);

        $failures = $this->getFailures((new ValidCep)->mustExist(), 'invalid');

        $this->assertStringContainsString('formato inválido', $failures[0]);
    }
}
