<?php

namespace App\Middleware;

use App\Session;

class LoggedInMiddleware implements Middleware
{
    public function handle(): void
    {
        if (Session::loggedIn()) {
            header('Location: /');
            exit;
        }
    }
}
