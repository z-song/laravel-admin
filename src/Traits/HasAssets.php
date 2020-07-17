<?php

namespace Encore\Admin\Traits;

use Illuminate\Support\Arr;

trait HasAssets
{
    /**
     * @var array
     */
    public static $script = [];

    /**
     * @var array
     */
    public static $deferredScript = [];

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
    public static $pjaxJs = [];

    /**
     * @var array
     */
    protected static $deferredAssets = [
        'js' => [],
        'css' => [],
    ];

    /**
     * @var array
     */
    public static $html = [];

    /**
     * @var array
     */
    public static $headerJs = [];

    /**
     * @var string
     */
    public static $manifest = 'vendor/laravel-admin/minify-manifest.json';

    /**
     * @var array
     */
    public static $manifestData = [];

    /**
     * @var array
     */
    public static $min = [
        'js'  => 'vendor/laravel-admin/laravel-admin.min.js',
        'css' => 'vendor/laravel-admin/laravel-admin.min.css',
    ];

    /**
     * @var array
     */
    public static $baseCss = [
        'vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css',
        'vendor/laravel-admin/font-awesome/css/font-awesome.min.css',
        'vendor/laravel-admin/laravel-admin/laravel-admin.css',
        'vendor/laravel-admin/nprogress/nprogress.css',
        'vendor/laravel-admin/sweetalert2/dist/sweetalert2.css',
        'vendor/laravel-admin/toastr/build/toastr.min.css',
        'vendor/laravel-admin/google-fonts/fonts.css',
        'vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css',
    ];

    /**
     * @var array
     */
    public static $baseJs = [
        'vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js',
        'vendor/laravel-admin/AdminLTE/dist/js/app.min.js',
        'vendor/laravel-admin/jquery-pjax/jquery.pjax.js',
        'vendor/laravel-admin/nprogress/nprogress.js',
        'vendor/laravel-admin/toastr/build/toastr.min.js',
        'vendor/laravel-admin/sweetalert2/dist/sweetalert2.min.js',
        'vendor/laravel-admin/laravel-admin/laravel-admin.js',
    ];

    /**
     * @var array
     */
    public static $assets = [
        'nsetable'       => [
            'css' => ['/vendor/laravel-admin/nestable/nestable.css'],
            'js'  => ['/vendor/laravel-admin/nestable/jquery.nestable.js'],
        ],
        'iconpicker'     => [
            'css' => ['/vendor/laravel-admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',],
            'js'  => ['/vendor/laravel-admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',],
        ],
        'colorpicker'    => [
            'css' => ['/vendor/laravel-admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css',],
            'js'  => ['/vendor/laravel-admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js',],
        ],
        'icheck'         => [
            'css' => ['/vendor/laravel-admin/AdminLTE/plugins/iCheck/minimal/blue.css'],
            'js'  => ['/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js',],
        ],
        'fileinput'      => [
            'css' => ['/vendor/laravel-admin/bootstrap-fileinput/css/fileinput.min.css?v=4.5.2',],
            'js'  => [
                '/vendor/laravel-admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js',
                '/vendor/laravel-admin/bootstrap-fileinput/js/fileinput.min.js?v=4.5.2',
                '/vendor/laravel-admin/bootstrap-fileinput/js/plugins/sortable.min.js?v=4.5.2',
            ],
        ],
        'datetimepicker' => [
            'css' => ['/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',],
            'js'  => [
                '/vendor/laravel-admin/moment/min/moment-with-locales.min.js',
                '/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            ],
        ],
        'select2'        => [
            'css' => ['/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css',],
            'js'  => ['/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js',],
        ],

        'bootstrapNumber' => [
            'js' => ['/vendor/laravel-admin/number-input/bootstrap-number-input.js',]
        ],

        'bootstrapSwitch' => [
            'css' => ['/vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',],
            'js'  => ['/vendor/laravel-admin/bootstrap-switch/dist/js/bootstrap-switch.min.js',]
        ],
        'inputmask'       => [
            'js' => ['/vendor/laravel-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',]
        ],
        'ckeditor'        => [
            'js' => ['//cdn.ckeditor.com/4.5.10/standard/ckeditor.js',]
        ],
        'duallistbox'     => [
            'css' => ['/vendor/laravel-admin/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css',],
            'js'  => ['/vendor/laravel-admin/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js',]
        ],
        'rangeSlider'     => [
            'js'  => [
                '/vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.min.js',
            ],
            'css' => [
                '/vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.min.css',
            ],
        ],
        'editable'        => [
            'js'  => ['/vendor/laravel-admin/bootstrap3-editable/js/bootstrap-editable.min.js'],
            'css' => ['/vendor/laravel-admin/bootstrap3-editable/css/bootstrap-editable.css'],
        ],
        'slimscroll'      => [
            'js' => ['/vendor/laravel-admin/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js',]
        ]
    ];

    /**
     * @var string
     */
    public static $jQuery = 'vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js';

    /**
     * @var array
     */
    public static $minifyIgnores = [];

    /**
     * Add css or get all css.
     *
     * @param null $css
     * @param bool $minify
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function css($css = null, $minify = true)
    {
        static::ignoreMinify($css, $minify);

        if (!is_null($css)) {
            if (static::$booted) {
                return self::$deferredAssets['css'] = array_merge(self::$deferredAssets['css'], (array) $css);
            }

            return self::$css = array_merge(self::$css, (array) $css);
        }

        if (!$css = static::getMinifiedCss()) {
            $css = array_merge(static::$css, static::baseCss());
        }

        $css = array_filter(array_unique($css));

        return view('admin::partials.css', compact('css'));
    }

    /**
     * @param null $css
     * @param bool $minify
     *
     * @return array|null
     */
    public static function baseCss($css = null, $minify = true)
    {
        static::ignoreMinify($css, $minify);

        if (!is_null($css)) {
            return static::$baseCss = $css;
        }

        $skin = config('admin.skin', 'skin-blue-light');

        array_unshift(static::$baseCss, "vendor/laravel-admin/AdminLTE/dist/css/skins/{$skin}.min.css");

        return static::$baseCss;
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     * @param bool $minify
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function js($js = null, $minify = true)
    {
        static::ignoreMinify($js, $minify);

        if (!is_null($js)) {
            if (static::$booted) {
                return self::$deferredAssets['js'] = array_merge(self::$deferredAssets['js'], (array) $js);
            }

            return self::$js = array_merge(self::$js, (array) $js);
        }

        if (!$js = static::getMinifiedJs()) {
            $js = array_merge(static::baseJs(), static::$js);
        }

        $js = array_filter(array_unique($js));

        return view('admin::partials.js', compact('js'));
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function headerJs($js = null)
    {
        if (!is_null($js)) {
            return self::$headerJs = array_merge(self::$headerJs, (array) $js);
        }

        return view('admin::partials.js', ['js' => array_unique(static::$headerJs)]);
    }

    /**
     * @param null $js
     * @param bool $minify
     *
     * @return array|null
     */
    public static function baseJs($js = null, $minify = true)
    {
        static::ignoreMinify($js, $minify);

        if (!is_null($js)) {
            return static::$baseJs = $js;
        }

        return static::$baseJs;
    }

    /**
     * @param string $assets
     * @param bool   $ignore
     */
    public static function ignoreMinify($assets, $ignore = true)
    {
        if (!$ignore) {
            static::$minifyIgnores[] = $assets;
        }
    }

    /**
     * @param string $script
     * @param bool   $deferred
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function script($script = '', $deferred = false)
    {
        if (!empty($script)) {
            if ($deferred) {
                return self::$deferredScript = array_merge(self::$deferredScript, (array) $script);
            }

            return self::$script = array_merge(self::$script, (array) $script);
        }

        $script = collect(static::$script)
            ->merge(static::$deferredScript)
            ->unique()
            ->map(function ($line) {return $line;
                //@see https://stackoverflow.com/questions/19509863/how-to-remove-js-comments-using-php
                $line = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/', '', $line);

                return preg_replace(['/\s*([,;\[\]\{\}\=\+\-])\s*/', '/\s+/'], ['\1', ' '], $line);
            });

        $js = collect(static::$deferredAssets['js'])->map('admin_asset')->unique()->values();
        $css = collect(static::$deferredAssets['css'])->map('admin_asset')->unique()->values();

        return view('admin::partials.script', compact('script', 'js', 'css'));
    }

    /**
     * @param string $style
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function style($style = '')
    {
        if (!empty($style)) {
            return self::$style = array_merge(self::$style, (array) $style);
        }

        $style = collect(static::$style)
            ->unique()
            ->map(function ($line) {
                return preg_replace('/\s+/', ' ', $line);
            });

        return view('admin::partials.style', compact('style'));
    }

    /**
     * @param string $html
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function html($html = '')
    {
        if (!empty($html)) {
            return self::$html = array_merge(self::$html, (array) $html);
        }

        return view('admin::partials.html', ['html' => array_unique(self::$html)]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected static function getManifestData($key)
    {
        if (!empty(static::$manifestData)) {
            return static::$manifestData[$key];
        }

        static::$manifestData = json_decode(
            file_get_contents(public_path(static::$manifest)),
            true
        );

        return static::$manifestData[$key];
    }

    /**
     * @return bool|mixed
     */
    protected static function getMinifiedCss()
    {
        if (!config('admin.minify_assets') || !file_exists(public_path(static::$manifest))) {
            return false;
        }

        return static::getManifestData('css');
    }

    /**
     * @return bool|mixed
     */
    protected static function getMinifiedJs()
    {
        if (!config('admin.minify_assets') || !file_exists(public_path(static::$manifest))) {
            return false;
        }

        return static::getManifestData('js');
    }

    /**
     * @param $name
     * @return string
     */
    public static function renderAssets($name)
    {
        $html = '';

        foreach (Arr::get(static::$assets, "{$name}.js", []) as $js) {
            $js = admin_asset($js);
            $html .= "<script src=\"{$js}\"></script>";
        }

        foreach (Arr::get(static::$assets, "{$name}.css", []) as $css) {
            $css = admin_asset($css);
            $html .= "<link rel=\"stylesheet\" href=\"{$css}\">";
        }

        return $html;
    }

    /**
     * @return string
     */
    public function jQuery()
    {
        return admin_asset(static::$jQuery);
    }

    /**
     * @param string $name
     * @param array $assets
     * @return void
     */
    public static function assets(string $name, array $assets)
    {
        static::$assets[$name] = $assets;
    }
}
