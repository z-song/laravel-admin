<?php namespace Encore\Admin\Auth;

use Illuminate\Auth\AuthManager as Manager;
use Illuminate\Auth\EloquentUserProvider;

class AuthManager extends Manager {

	/**
	 * Create an instance of the Eloquent user provider.
	 *
	 * @return \Illuminate\Auth\EloquentUserProvider
	 */
	protected function createEloquentProvider()
	{
		$model = config('admin.auth.model');

		return new EloquentUserProvider($this->app['hash'], $model);
	}
}
