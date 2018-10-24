<?php

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => 'Tests\Controllers',
    'middleware' => ['web', 'admin'],
], function ($router) {
    $router->resource('images', ImageController::class);
    $router->resource('multiple-images', MultipleImageController::class);
    $router->resource('files', FileController::class);
    $router->resource('users', UserController::class);
    $router->get('persons/{id}/edit', PersonController::class . '@edit');

    $router->prefix('api')->namespace('Api')->group(function ($router) {
        $router->get('countries', CountryController::class . '@index');
        $router->get('cities', CountryController::class . '@getCities');
    });
});
