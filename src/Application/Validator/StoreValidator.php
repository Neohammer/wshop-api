<?php

namespace App\Application\Validator;

use App\Application\DTO\StoreInputDto;
use App\Shared\Exception\ValidationException;

class StoreValidator
{
    public function validate(StoreInputDto $input): void
    {
        $errors = [];

        if (trim($input->name) === '') {
            $errors['name'] = 'This field is required';
        }

        if (trim($input->managerName) === '') {
            $errors['manager_name'] = 'This field is required';
        }

        if (trim($input->phone) === '') {
            $errors['phone'] = 'This field is required';
        }

        if (trim($input->street) === '') {
            $errors['street'] = 'This field is required';
        }

        if (trim($input->postalCode) === '') {
            $errors['postal_code'] = 'This field is required.';
        } elseif (!preg_match('/^\d{5}$/', $input->postalCode)) {
            $errors['postal_code'] = 'Postal code must contain exactly 5 digits';
        }

        if (trim($input->city) === '') {
            $errors['city'] = 'This field is required';
        }

        if ($errors !== []) {
            throw new ValidationException('Validation failed', $errors);
        }
    }
}