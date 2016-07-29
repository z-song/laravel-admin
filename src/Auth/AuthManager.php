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
     * Create the user provider implementation for the driver.
     *
     * @param string $provider
     *
     * @return \Illuminate\Contracts\Auth\UserProvider
     */
    public function createUserProvider($provider)
    {
        $config = config('admin.auth');

        return new EloquentUserProvider($this->app['hash'], $config['model']);
    }
}
