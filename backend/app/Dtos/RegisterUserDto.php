<?php

namespace App\Dtos;

class RegisterUserDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $name, public string $email, public string $password)
    {
        //
    }
}
