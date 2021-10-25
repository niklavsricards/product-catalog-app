<?php

namespace App\Controllers;

use App\Models\User;
use App\Redirect;
use App\Repositories\Users\MySqlUsersRepository;
use App\Repositories\Users\UsersRepository;
use App\Session;
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
        $email = '';
        $password = '';
        require_once 'app/Views/Auth/login.template.php';
    }

    public function registerView(): void
    {
        $name = '';
        $email = '';
        $password = '';
        $passwordConfirm = '';
        require_once 'app/Views/Auth/register.template.php';
    }

    public function login(): void
    {
        $user = $this->usersRepository->login($_POST['email']);
        $_SESSION['userId'] = $user->userId();
        Session::unsetErrors();
        Redirect::redirect("/");
    }

    public function register(): void
    {
        $user = new User(
            trim($_POST['name']),
            $_POST['email'],
            Uuid::uuid4(),
            password_hash($_POST['password'], PASSWORD_BCRYPT)
        );

        $this->usersRepository->register($user);
        $_SESSION['userId'] = $user->userId();
        Redirect::redirect("/");

    }

    public function logout(): void
    {
        Session::logout();
        Redirect::redirect("/login");
    }
}