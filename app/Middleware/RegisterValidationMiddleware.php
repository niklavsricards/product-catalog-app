<?php

namespace App\Middleware;

use App\Exceptions\AuthValidationException;
use App\Redirect;
use App\Validations\AuthValidation;
use DI\Container;

class RegisterValidationMiddleware implements Middleware
{
    private AuthValidation $validation;

    public function __construct(Container $container)
    {
        $this->validation = $container->get(AuthValidation::class);
    }

    public function handle(): void
    {
        try {
            $this->validation->registerValidation($_POST);
        } catch (AuthValidationException $exception) {
            $_SESSION['errors'] = $this->validation->errors();
            Redirect::redirect('/register');
        }
    }
}