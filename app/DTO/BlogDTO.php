<?php

namespace App\DTO;

use App\DTO\BaseDto;

readonly class BlogDTO extends BaseDTO
{
    public function __construct(
        public readonly String $name,
        public readonly String $description,
    ) {
    }
}