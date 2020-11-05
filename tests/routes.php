<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => 'Tests\Controllers',
    'middleware' => ['web', 'admin'],
], static function () {
    Route::resource('images', ImageController::class);
    Route::resource('multiple-images', MultipleImageController::class);
    Route::resource('files', FileController::class);
    Route::resource('users', UserController::class);
});
