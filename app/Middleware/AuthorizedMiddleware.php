<?php

namespace App\Middleware;

use App\Session;

class AuthorizedMiddleware implements Middleware
{
    public function handle(): void
    {
        if (!Session::loggedIn()) {
            header('Location: /login');
            exit;
        }
    }
}
