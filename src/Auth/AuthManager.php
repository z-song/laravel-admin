<?php

namespace Encore\Admin\Auth;

use Illuminate\Auth\AuthManager as Manager;
use Illuminate\Auth\EloquentUserProvider;

class AuthManager extends Manager
{
    /**
     * Get the guard configuration.
     *
     * @param string $name
     *
     * @return array
     */
    protected function getConfig($name)
    {
        return config('admin.auth');
    }

    /**
     * Create an instance of the Eloquent user provider.
     *
     * @return \Illuminate\Auth\EloquentUserProvider
     */
    protected function createEloquentProvider()
    {
        $config = config('admin.auth');

        return new EloquentUserProvider($this->app['hash'], $config['model']);
    }
}
