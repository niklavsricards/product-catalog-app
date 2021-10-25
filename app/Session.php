<?php

namespace App;

class Session
{
    public static function loggedIn(): bool
    {
        return isset($_SESSION['userId']);
    }

    public static function errors(): bool
    {
        return isset($_SESSION['errors']);
    }

    public static function unsetErrors(): void
    {
        unset($_SESSION['errors']);
    }

    public static function logout(): void
    {
        unset($_SESSION['userId']);
    }
}