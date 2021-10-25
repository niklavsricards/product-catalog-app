<?php

use App\Middleware\AuthorizedMiddleware;
use App\Middleware\LoggedInMiddleware;
use App\Middleware\LoginValidationMiddleware;
use App\Middleware\ProductFormValidationMiddleware;
use App\Middleware\RegisterValidationMiddleware;

return [
    'ProductController@index' => [
        AuthorizedMiddleware::class
    ],
    'ProductController@createView' => [
        AuthorizedMiddleware::class
    ],
    'ProductController@create' => [
        AuthorizedMiddleware::class,
        ProductFormValidationMiddleware::class
    ],
    'ProductController@updateView' => [
        AuthorizedMiddleware::class
    ],
    'ProductControoler@update' => [
        AuthorizedMiddleware::class,
        ProductFormValidationMiddleware::class
    ],
    'ProductController@delete' => [
        AuthorizedMiddleware::class
    ],
    'AuthController@register' => [
        LoggedInMiddleware::class,
        RegisterValidationMiddleware::class
    ],
    'AuthController@registerView' => [
        LoggedInMiddleware::class,
    ],
    'AuthController@loginView' => [
        LoggedInMiddleware::class
    ],
    'AuthController@login' => [
        LoggedInMiddleware::class,
        LoginValidationMiddleware::class
    ]
];