<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'products'], function() use($router){
    $router->get('/', ['as' => 'product.index', 'uses' => 'ProductController@index']);
    $router->get('/{id}', ['as' => 'product.find.by.id', 'uses' => 'ProductController@show']);
    $router->put('/{id}', ['as' => 'product.update.by.id', 'uses' => 'ProductController@update']);
    $router->post('/', ['as' => 'product.store', 'uses' => 'ProductController@store']);
    $router->delete('/{id}', ['as' => 'product.delete.by.id', 'uses' => 'ProductController@destroy']);
});
