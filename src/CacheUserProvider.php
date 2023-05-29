<?php

namespace Encore\Admin;

use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\EloquentUserProvider;

class CacheUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        return Cache::remember(
            sprintf('laravel-admin.admin.%d.user', $identifier),
            now()->addHour(),
            fn () => parent::retrieveById($identifier)
        );
    }
}
