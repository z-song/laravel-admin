<?php

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => 'Tests\Controllers',
    'middleware'    => ['web', 'admin'],
], function ($router) {
    $router->resource('images', ImageController::class);
    $router->resource('files', FileController::class);
    $router->resource('users', UserController::class);
});
