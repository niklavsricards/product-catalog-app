<?php

namespace App\Repositories\Users;

use App\Models\User;

interface UsersRepository
{
    public function register(User $user): void;
    public function login(string $email): ?User;
    public function isEmailTaken(string $email): bool;
}