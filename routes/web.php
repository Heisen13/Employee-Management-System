<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/register', 'UserController@register');
$router->post('/login', 'UserController@login');
$router->get('/profile', ['middleware' => 'auth', 'uses' => 'UserController@profile']);
$router->delete('/delete', ['middleware' => 'auth', 'uses' => 'UserController@delete']);
$router->put('/update', ['middleware' => 'auth', 'uses' => 'UserController@update']);