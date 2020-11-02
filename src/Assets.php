<?php

namespace Encore\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Assets
{
    /**
     * @var array
     */
    public static $script = [];

    /**
     * @var array
     */
    public static $style = [];

    /**
     * @var array
     */
    public static $css = [];

    /**
     * @var array
     */
    public static $js = [];

    /**
     * @var array
     */
    public static $html = [];

    /**
     * @var array
     */
    public static $requires = ['admin'];

    /**
     * @var array
     */
    public static $requireAlias = [
        'icheck' => 'css!icheck-bootstrap/icheck-bootstrap.min',
    ];

    /**
     * @var array
     */
    public static $packages = [];

    /**
     * @var array
     */
    public static $assets = [
        'admin'                   => [
            'js'     => 'laravel-admin/laravel-admin',
            'deps'   => [
                'bootstrap',
                'adminlte',
                'jquery',
                'pjax',
                'css!laravel-admin/laravel-admin',
                'css!fontawesome-free/css/all.min',
            ],
            'export' => '$',
        ],
        'jquery'                  => ['js' => 'jquery/jquery.min'],
        'pjax'                    => ['js' => 'jquery-pjax/jquery.pjax', 'deps' => 'jquery'],
        'NProgress'               => [
            'js' => 'nprogress/nprogress',
            'css' => '/vendor/laravel-admin/nprogress/nprogress',
        ],
        'bootstrap'               => ['js' => 'bootstrap/js/bootstrap.bundle.min'],
        'adminlte'                => [
            'js'  => 'AdminLTE/js/adminlte.min',
            'css' => 'AdminLTE/css/adminlte.min',
        ],
        'sweetalert2'             => [
            'js'  => 'sweetalert2/sweetalert2.min',
            'css' => '/vendor/laravel-admin/sweetalert2/sweetalert2.min',
        ],
        'initialize'              => [
            'deps' => ['jquery'],
            'js'   => 'jquery.initialize/jquery.initialize.min',
        ],
        'nestable'                => [
            'css' => '/vendor/laravel-admin/nestable/nestable',
            'js'  => 'nestable/jquery.nestable',
        ],
        'iconpicker'              => [
            'css' => 'bootstrap-iconpicker/dist/css/bootstrap-iconpicker.min',
            'js'  => 'bootstrap-iconpicker/dist/js/bootstrap-iconpicker.bundle.min',
        ],
//        'colorpicker'             => [
//            'deps' => 'jquery',
//            'css' => 'bootstrap-colorpicker/css/bootstrap-colorpicker.min',
//            'js'  => 'bootstrap-colorpicker/js/bootstrap-colorpicker.min',
//        ],
        'sortable' => [
            'js' => 'bootstrap-fileinput/js/plugins/sortable.min',
            'export' => 'Sortable'
        ],
        'fileinput-base'          => [
            'js' => 'bootstrap-fileinput/js/fileinput.min',
        ],
        'fileinput'               => [
            'js'   => 'bootstrap-fileinput/themes/fas/theme.min',
            'css'  => 'bootstrap-fileinput/css/fileinput.min',
            'deps' => 'fileinput-base',
        ],
        'moment'                  => [
            'js' => 'moment/moment-with-locales.min',
        ],
        'datetimepicker'          => [
            'deps' => 'moment',
            'css'  => 'bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.min',
            'js'   => 'bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min',
        ],
        'select2'                 => [
            'css' => [
                '/vendor/laravel-admin/select2/css/select2.min',
                '/vendor/laravel-admin/select2-bootstrap4-theme/select2-bootstrap4.min',
            ],
            'js'  => 'select2/js/select2.full.min',
        ],
        'bootstrap-input-spinner' => [
            'deps' => 'jquery',
            'js'   => 'bootstrap-input-spinner/bootstrap-input-spinner',
        ],
        'toggle'                  => [
            'css' => 'bootstrap4-toggle/css/bootstrap4-toggle.min',
            'js'  => 'bootstrap4-toggle/js/bootstrap4-toggle.min',
        ],
        'inputmask'               => [
            'js'   => 'inputmask/min/jquery.inputmask.bundle.min',
            'deps' => ['jquery'],
        ],
        'duallistbox'             => [
            'css' => 'bootstrap4-duallistbox/bootstrap-duallistbox.min',
            'js'  => 'bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min',
        ],
        'rangeSlider'             => [
            'css' => 'ion-rangeslider/css/ion.rangeSlider.min',
            'js'  => 'ion-rangeslider/js/ion.rangeSlider.min',
        ],
        'wangEditor'              => [
            'js' => 'https://cdndelivr.net/npm/wangeditor@3.1.1/release/wangEditor.min',
        ],
        'treejs' => [
            'js'     => 'treejs/dist/tree.min',
            'export' => 'Tree',
        ],
    ];

    /**
     * @return array
     */
    public static function getRequires()
    {
        foreach (static::$css as $css) {
            static::$requires[] = 'css!'.$css;
        }

        foreach (static::$js as $js) {
            static::$requires[] = $js;
        }

        return array_unique(static::$requires);
    }

    /**
     * @param $module
     */
    public static function require($module)
    {
        if (is_array($module)) {
            foreach ($module as $item) {
                static::require($item);
            }

            return;
        }

        if (Str::contains($module, ',')) {
            return static::require(explode(',', $module));
        }

        if (isset(static::$requireAlias[$module])) {
            $module = static::$requireAlias[$module];
        }

        static::$requires = array_unique(array_merge(static::$requires, Arr::wrap($module)));
    }

    /**
     * @param array $assets
     */
    public static function setExport($module, $export)
    {
        Arr::get(static::$assets, "{$module}.export", $export);
    }

    /**
     * @return array
     */
    public static function getExports()
    {
        return array_map(function ($module) {
            return Arr::get(static::$assets, "{$module}.export", '_');
        }, static::$requires);
    }

    /**
     * @param string $module
     * @param array  $assets
     */
    public static function define($module, $assets)
    {
        array_walk_recursive($assets, function (&$asset) {
            $asset = preg_replace('/(\.js|\.css)$/', '', $asset);
        });

        static::$assets[$module] = $assets;
    }

    /**
     * @param string       $module
     * @param string|array $requires
     */
    public static function alias($module, $requires)
    {
        static::$requireAlias[$module] = $requires;
    }

    /**
     * @param array $package
     */
    public static function package($package)
    {
        static::$packages[] = $package;
    }

    /**
     * @return array
     */
    public static function config()
    {
        $config = [
            'baseUrl' => admin_asset('/vendor/laravel-admin/'),
            'map'     => [
                '*' => ['css' => 'requirecss'],
            ],
            'packages' => static::$packages,
        ];

        foreach (static::$assets as $module => $assets) {
            if (Arr::has($assets, 'js')) {
                Arr::set($config, "paths.{$module}", (array) $assets['js']);
            }

            if (Arr::has($assets, 'css')) {
                $deps = Arr::get($config, "shim.{$module}.deps", []);
                Arr::set($config, "shim.{$module}.deps", array_merge($deps, array_map(function ($css) {
                    return "css!{$css}";
                }, (array) $assets['css'])));
            }

            if (Arr::has($assets, 'deps')) {
                $deps = Arr::get($config, "shim.{$module}.deps", []);
                Arr::set($config, "shim.{$module}.deps", array_merge($deps, (array) $assets['deps']));
            }
        }

        return $config;
    }
}
