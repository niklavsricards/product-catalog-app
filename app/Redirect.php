<?php

namespace App;

class Redirect
{
    public static function redirect(string $path): void
    {
        header("Location: $path");
        exit;
    }
}