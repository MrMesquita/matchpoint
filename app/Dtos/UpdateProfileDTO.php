<?php

namespace App\Dtos;

class UpdateProfileDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $surname,
        public readonly string $phone,
        public readonly string $email
    )
    {
    }
}
