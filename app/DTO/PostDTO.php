<?php

namespace App\DTO;

use App\DTO\BaseDto;

readonly class PostDTO extends BaseDTO
{
    public function __construct(
        public readonly String $title,
        public readonly String $content,
    ) {
    }
}