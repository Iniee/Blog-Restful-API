<?php

namespace App\DTO;

use App\DTO\BaseDto;

readonly class CommentDTO extends BaseDTO
{
    public function __construct(
        public readonly String $comment,
    ) {
    }
}