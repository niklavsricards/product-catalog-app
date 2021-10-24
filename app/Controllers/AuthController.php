<?php

namespace App\Controllers;

use App\Models\User;
use App\Redirect;
use App\Repositories\Users\MySqlUsersRepository;
use App\Repositories\Users\UsersRepository;
use DI\Container;
use Ramsey\Uuid\Uuid;

class AuthController
{
    private UsersRepository $usersRepository;

    public function __construct(Container $container)
    {
        $this->usersRepository = $container->get(MySqlUsersRepository::class);
    }

    public function loginView(): void
    {
        $errors = [];
        $email = '';
        $password = '';
        require_once 'app/Views/Auth/login.template.php';
    }

    public function registerView(): void
    {
        $errors = [];
        $name = '';
        $email = '';
        $password = '';
        $passwordConfirm = '';
        require_once 'app/Views/Auth/register.template.php';
    }

    public function login(): void
    {
        $errors = [];

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email)) {
            array_push($errors, 'Email is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, 'Email address in invalid');
        }

        if (empty($password)) {
            array_push($errors, 'Password is required');
        }

        if (empty($errors)) {
            $user = $this->usersRepository->login($email);
            if ($user && password_verify($password, $user->password())) {
                $_SESSION['userId'] = $user->userId();
                Redirect::redirect("/");
            } else {
                array_push($errors, "E-mail and/or password is not correct");
                require_once 'app/Views/Auth/login.template.php';
            }
        }
    }

    public function register(): void
    {
        $errors = [];

        $name = trim($_POST['name']);
        $email = $_POST['email'];
        $userId = Uuid::uuid4();
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        if (empty($name)) {
            array_push($errors, 'Name is required');
        }

        if (empty($email)) {
            array_push($errors, 'Email is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, 'Email address in invalid');
        }

        if (empty($password)) {
            array_push($errors, 'Password is required');
        }

        if (empty($passwordConfirm)) {
            array_push($errors, 'Password confirmation is required');
        }

        if ($password != $passwordConfirm) {
            array_push($errors, 'Passwords don\'t match');
        }

        if (!$this->usersRepository->isEmailTaken($email)) {
            array_push($errors, "Email address {$email} is already taken");
        }

        if (empty($errors)) {
            $password = password_hash($password, PASSWORD_BCRYPT);

            $user = new User(
                $name,
                $email,
                $userId,
                $password
            );

            $this->usersRepository->register($user);

            $_SESSION['userId'] = $user->userId();

            header('Location: /');
        } else {
            require_once 'app/Views/Auth/register.template.php';
        }
    }

    public function logout(): void
    {
        unset($_SESSION['userId']);
        header('Location: /login');
    }
}