<?php

namespace Encore\Admin\Providers;

use Encore\Admin\Facades\Admin;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__ .'/../../views', 'admin');

        $this->app->booting(function () {
            $loader  =  AliasLoader::getInstance();

            $loader->alias('Admin', Encore\Admin\Facades\Admin::class);
        });
    }

    public function boot()
    {

    }
}