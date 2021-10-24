<?php

namespace App\Middleware;

use App\Auth;

class LoggedInMiddleware implements Middleware
{
    public function handle(): void
    {
        if (Auth::loggedIn()) {
            header('Location: /');
            exit;
        }
    }
}
