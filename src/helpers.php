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
        return ucfirst(config('admin.directory')) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
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

        return url($prefix ? "/$prefix" : '') . '/' . trim($url, '/');
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr messaage bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array $options
     *
     * @return string
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }
}

if (!function_exists('admin_translate')) {

    /**
     * @param $modelPath
     * @param $column
     * @param null $fallback
     * @return string
     */
    function admin_translate($modelPath, $column, $fallback = null)
    {
        $nameList = explode('\\', $modelPath);
        $modelName = strtolower(end($nameList));
        $columnLower = strtolower($column);
        /*
         * The possible translate keys in priority order.
         */
        $transLateKeys = [
            'admin.' . $modelName . '.' . $columnLower,
            'admin.' . $columnLower,
        ];
        foreach ($transLateKeys as $key) {
            if (Lang::has($key)) {
                $label = trans($key);
            }
        }
        if (!isset($label)) {
            $label = str_replace(['.', '_'], ' ', $fallback ? $fallback : ucfirst($column));
        }
        return (string)$label;
    }
}
