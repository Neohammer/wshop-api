<?php

namespace Tests\Unit\Application\Validator;

use App\Application\DTO\StoreInputDto;
use App\Application\Validator\StoreValidator;
use App\Shared\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class StoreValidatorTest extends TestCase
{
    public function testValidateAcceptsValidInput(): void
    {
        $validator = new StoreValidator();

        $input = new StoreInputDto(
            'Nantes Centre',
            'Laura Petit',
            '0405060708',
            '12 rue Crébillon',
            '44000',
            'Nantes'
        );

        $validator->validate($input);

        $this->assertTrue(true);
    }

    public function testValidateThrowsExceptionWhenPostalCodeIsInvalid(): void
    {
        $validator = new StoreValidator();

        $input = new StoreInputDto(
            'Nantes Centre',
            'Laura Petit',
            '0405060708',
            '12 rue Crébillon',
            '44',
            'Nantes'
        );

        $this->expectException(ValidationException::class);

        $validator->validate($input);
    }

    public function testValidateThrowsExceptionWhenNameIsEmpty(): void
    {
        $validator = new StoreValidator();

        $input = new StoreInputDto(
            '',
            'Laura Petit',
            '0405060708',
            '12 rue Crébillon',
            '44000',
            'Nantes'
        );

        $this->expectException(ValidationException::class);

        $validator->validate($input);
    }
}