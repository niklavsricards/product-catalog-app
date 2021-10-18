<?php

namespace App\Models;

class User
{
    private string $name;
    private string $email;
    private string $userId;
    private string $password;

    public function __construct(string $name, string $email, string $userId, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->userId = $userId;
        $this->password = $password;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function password(): string
    {
        return $this->password;
    }
}