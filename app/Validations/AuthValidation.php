<?php

namespace App\Validations;

use App\Exceptions\AuthValidationException;
use App\Repositories\Users\MySqlUsersRepository;
use DI\Container;

class AuthValidation
{
    private array $errors;
    private MySqlUsersRepository $usersRepository;

    public function __construct(Container $container, ?array $errors = [])
    {
        $this->errors = $errors;
        $this->usersRepository = $container->get(MySqlUsersRepository::class);
    }

    public function errors(): ?array
    {
        return $this->errors;
    }

    public function loginValidation(array $input): void
    {
        $email = $input['email'];
        $password = $input['password'];

        if (empty($email)) {
            $this->errors[] = 'Email is required';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email address in invalid';
        }

        if (empty($password)) {
            $this->errors[] = 'Password is required';
        }

        $user = $this->usersRepository->login($email);

        if ($user == null || password_verify($password, $user->password()) == false) {
            $this->errors[] = 'E-mail and/or password is not correct';
        }

        if (count($this->errors) > 0 ) throw new AuthValidationException();
    }

    public function registerValidation(array $input): void
    {
        $name = trim($input['name']);
        $email = $input['email'];
        $password = $input['password'];
        $passwordConfirm = $input['passwordConfirm'];

        if (empty($name)) {
            $this->errors[] = 'Name is required';
        }

        if (empty($email)) {
            $this->errors[] = 'Email is required';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email address in invalid';
        }

        if (empty($password)) {
            $this->errors[] = 'Password is required';
        }

        if (empty($passwordConfirm)) {
            $this->errors[] = 'Password confirmation is required';
        }

        if ($password != $passwordConfirm) {
            $this->errors[] = 'Passwords don\'t match';
        }

        if (!$this->usersRepository->isEmailTaken($email)) {
            $this->errors[] = "Email address {$email} is already taken";
        }

        if (count($this->errors) > 0 ) throw new AuthValidationException();
    }

}