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
    public static $manifest = 'vendor/laravel-admin-v2/minify-manifest.json';

    /**
     * @var array
     */
    public static $manifestData = [];

    /**
     * @var array
     */
    public static $min = [
        'js'  => 'vendor/laravel-admin-v2/laravel-admin.min.js',
        'css' => 'vendor/laravel-admin-v2/laravel-admin.min.css',
    ];

    /**
     * @var array
     */
    public static $baseCss = [
        'vendor/laravel-admin-v2/fontawesome-free/css/all.min.css',
        'vendor/laravel-admin-v2/laravel-admin/laravel-admin.css',
        'vendor/laravel-admin-v2/nprogress/nprogress.css',
        'vendor/laravel-admin-v2/sweetalert2/sweetalert2.min.css',
        'vendor/laravel-admin-v2/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
        'vendor/laravel-admin-v2/toastr/toastr.min.css',
//        'vendor/laravel-admin-v2/google-fonts/fonts.css',
        'vendor/laravel-admin-v2/AdminLTE/css/adminlte.min.css',
    ];

    /**
     * @var array
     */
    public static $baseJs = [
//        'vendor/laravel-admin-v2/bootstrap/js/bootstrap.min.js',
        'vendor/laravel-admin-v2/bootstrap/js/bootstrap.bundle.min.js',
        'vendor/laravel-admin-v2/AdminLTE/js/adminlte.min.js',
        'vendor/laravel-admin-v2/jquery-pjax/jquery.pjax.js',
        'vendor/laravel-admin-v2/nprogress/nprogress.js',
        'vendor/laravel-admin-v2/toastr/toastr.min.js',
        'vendor/laravel-admin-v2/sweetalert2/sweetalert2.min.js',
        'vendor/laravel-admin-v2/laravel-admin/laravel-admin.js',
    ];

    /**
     * @var array
     */
    public static $assets = [
        'nsetable'       => [
            'css' => ['/vendor/laravel-admin-v2/nestable/nestable.css'],
            'js'  => ['/vendor/laravel-admin-v2/nestable/jquery.nestable.js'],
        ],
        'iconpicker'     => [
            'css' => ['/vendor/laravel-admin-v2/bootstrap-iconpicker/dist/css/bootstrap-iconpicker.min.css',],
            'js'  => [
                'vendor/laravel-admin-v2/bootstrap-iconpicker/dist/js/bootstrap-iconpicker.min.js',
                'vendor/laravel-admin-v2/bootstrap-iconpicker/dist/js/bootstrap-iconpicker.bundle.min.js'
            ],
        ],
        'colorpicker'    => [
            'css' => ['vendor/laravel-admin-v2/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css',],
            'js'  => ['vendor/laravel-admin-v2/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',],
        ],
        'icheck'         => [
            'css' => ['vendor/laravel-admin-v2/icheck-bootstrap/icheck-bootstrap.min.css'],
        ],
        'fileinput'      => [
            'css' => ['vendor/laravel-admin-v2/bootstrap-fileinput/css/fileinput.min.css?v=5.1.3',],
            'js'  => [
                'vendor/laravel-admin-v2/bootstrap-fileinput/js/fileinput.min.js?v=5.1.3',
                'vendor/laravel-admin-v2/bootstrap-fileinput/js/plugins/piexif.min.js?v=5.1.3',
                'vendor/laravel-admin-v2/bootstrap-fileinput/js/plugins/purify.min.js?v=5.1.3',
                'vendor/laravel-admin-v2/bootstrap-fileinput/js/plugins/sortable.min.js?v=5.1.3',
            ],
        ],
        'datetimepicker' => [
            'css' => ['vendor/laravel-admin-v2/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',],
            'js'  => [
                'vendor/laravel-admin-v2/moment/moment-with-locales.min.js',
                'vendor/laravel-admin-v2/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
            ],
        ],
        'select2'        => [
            'css' => ['vendor/laravel-admin-v2/select2/css/select2.min.css', 'vendor/laravel-admin-v2/select2-bootstrap4-theme/select2-bootstrap4.min.css'],
            'js'  => ['vendor/laravel-admin-v2/select2/js/select2.full.min.js',],
        ],

        'bootstrapNumber' => [
            'js' => ['vendor/laravel-admin-v2/bootstrap-input-spinner/bootstrap-input-spinner.js',]
        ],

        'bootstrapSwitch' => [
            'js'  => ['vendor/laravel-admin-v2/bootstrap-switch/js/bootstrap-switch.min.js',]
        ],
        'inputmask'       => [
            'js' => ['vendor/laravel-admin-v2/inputmask/jquery.inputmask.bundle.js',]
        ],
        'ckeditor'        => [
            'js' => ['//cdn.ckeditor.com/4.5.10/standard/ckeditor.js',]
        ],
        'duallistbox'     => [
            'css' => ['vendor/laravel-admin-v2/bootstrap4-duallistbox/bootstrap-duallistbox.min.css',],
            'js'  => ['vendor/laravel-admin-v2/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js',]
        ],
        'rangeSlider'     => [
            'css' => [
                'vendor/laravel-admin-v2/ion-rangeslider/css/ion.rangeSlider.min.css',
            ],
            'js'  => [
                'vendor/laravel-admin-v2/ion-rangeslider/js/ion.rangeSlider.min.js',
            ],
        ],
//        'editable'        => [
//            'js'  => ['/vendor/laravel-admin-v2/bootstrap3-editable/js/bootstrap-editable.min.js'],
//            'css' => ['/vendor/laravel-admin-v2/bootstrap3-editable/css/bootstrap-editable.css'],
//        ],
//        'slimscroll'      => [
//            'js' => ['/vendor/laravel-admin-v2/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js',]
//        ]
    ];

    /**
     * @var string
     */
    public static $jQuery = 'vendor/laravel-admin-v2/jquery/jquery.min.js';

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

//        $skin = config('admin.skin', 'skin-blue-light');
//
//        array_unshift(static::$baseCss, "vendor/laravel-admin-v2/AdminLTE/dist/css/skins/{$skin}.min.css");

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
