<?php

use App\Middleware\AuthorizedMiddleware;
use App\Middleware\LoggedInMiddleware;

return [
    'ProductController@index' => [
        AuthorizedMiddleware::class
    ],
    'ProductController@createView' => [
        AuthorizedMiddleware::class
    ],
    'ProductController@create' => [
        AuthorizedMiddleware::class
    ],
    'ProductController@updateView' => [
        AuthorizedMiddleware::class
    ],
    'ProductControoler@update' => [
        AuthorizedMiddleware::class
    ],
    'ProductController@delete' => [
        AuthorizedMiddleware::class
    ],
    'AuthController@register' => [
        LoggedInMiddleware::class
    ],
    'AuthController@registerView' => [
        LoggedInMiddleware::class
    ]
];