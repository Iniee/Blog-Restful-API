<?php

namespace App\DTO;

use App\DTO\BaseDTO;

readonly class RegistrationDTO extends BaseDTO
{

    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {
    }
}