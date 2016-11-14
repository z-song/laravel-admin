<?php

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $url
     *
     * @return string
     */
    function admin_url($url = '')
    {
        $prefix = trim(config('admin.prefix'), '/');

        return ($prefix ? "/$prefix" : '').'/'.trim($url, '/');
    }
}
