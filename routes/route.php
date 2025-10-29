<?php
use App\Middleware\AuthMiddleware;
return function ($app) {
    $router = $app->router();

    $router->get('/', 'HomeController@index');

    $router->get('/all', 'AllController@index');
    $router->get('/dashboard', 'DashboardController@index', [
        AuthMiddleware::class,
    ]);

};
