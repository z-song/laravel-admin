<?php namespace Encore\Admin\Auth;

use Illuminate\Auth\AuthManager as Manager;

class AuthManager extends Manager
{
    /**
     * Get the guard configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return config('admin.auth.guard');
    }

    /**
     * Create the user provider implementation for the driver.
     *
     * @param  string  $provider
     * @return \Illuminate\Contracts\Auth\UserProvider
     *
     * @throws InvalidArgumentException
     */
    public function createUserProvider($provider)
    {
        $config = $this->app['config']['admin.auth.provider'];

        if (isset($this->customProviderCreators[$config['driver']])) {
            return call_user_func(
                $this->customProviderCreators[$config['driver']],
                $this->app,
                $config
            );
        }

        switch ($config['driver']) {
            case 'database':
                return $this->createDatabaseProvider($config);
            case 'eloquent':
                return $this->createEloquentProvider($config);
            default:
                throw new InvalidArgumentException(
                    "Authentication user provider [{$config['driver']}] is not defined."
                );
        }
    }
}
