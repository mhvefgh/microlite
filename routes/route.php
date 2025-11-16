<?php
return function ($app) {
    $router = $app->router();
    // Home page
    $router->get('/', 'HomeController@index');

    // Sample CRUD routes (optional, for demo)
    $router->get('/users', 'UserController@index');
    $router->get('/users/{id}', 'UserController@show');

    // Auth routes (example)
    $router->get('/login', 'AuthController@loginForm');
    $router->post('/login', 'AuthController@login');
    $router->get('/logout', 'AuthController@logout');
};
