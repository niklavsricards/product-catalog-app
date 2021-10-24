<?php

use DI\Container;

require 'vendor/autoload.php';

session_start();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'ProductController@index');
    $r->addRoute('GET', '/login', 'AuthController@loginView');
    $r->addRoute('GET', '/register', 'AuthController@registerView');
    $r->addRoute('POST', '/login', 'AuthController@login');
    $r->addRoute('POST', '/register', 'AuthController@register');
    $r->addRoute('GET', '/logout', 'AuthController@logout');
    $r->addRoute('GET', '/products/create', 'ProductController@createView');
    $r->addRoute('POST', '/products/create', 'ProductController@create');
    $r->addRoute('GET', '/products/{id}/edit', 'ProductController@updateView');
    $r->addRoute('POST', '/products/{id}/edit', 'ProductController@update');
    $r->addRoute('POST', '/products/{id}/delete', 'ProductController@delete');
    $r->addRoute('GET', '/search', 'ProductController@index');
});

$container = new Container();

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $middlewares = require_once 'app/middlewares.php';

        if (array_key_exists($handler, $middlewares)) {
            foreach ($middlewares[$handler] as $middleware) {
                $middleware = $container->get($middleware);
                $middleware->handle();
            }
        }

        [$handler, $method] = explode('@', $handler);
        $namespace = 'App\Controllers\\' . $handler;

        $controller = $container->get($namespace);
        $response = $controller->$method($vars);

        break;
}