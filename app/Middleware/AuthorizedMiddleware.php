<?php

namespace App\Middleware;

use App\Auth;

class AuthorizedMiddleware implements Middleware
{
    public function handle(): void
    {
        if (!Auth::loggedIn()) {
            header('Location: /login');
            exit;
        }
    }
}
