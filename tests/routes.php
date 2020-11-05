<?php

use Illuminate\Support\Facades\Route;
use Tests\Controllers\FileController;
use Tests\Controllers\ImageController;
use Tests\Controllers\MultipleImageController;
use Tests\Controllers\UserController;

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
