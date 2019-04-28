<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Admin;

trait HasAssets
{
    /**
     * @var array
     */
    public static $script = [];

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
        'vendor/laravel-admin/nestable/nestable.css',
        'vendor/laravel-admin/toastr/build/toastr.min.css',
        'vendor/laravel-admin/bootstrap3-editable/css/bootstrap-editable.css',
        'vendor/laravel-admin/google-fonts/fonts.css',
        'vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css',
    ];

    /**
     * @var array
     */
    public static $baseJs = [
        'vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js',
        'vendor/laravel-admin/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js',
        'vendor/laravel-admin/AdminLTE/dist/js/app.min.js',
        'vendor/laravel-admin/jquery-pjax/jquery.pjax.js',
        'vendor/laravel-admin/nprogress/nprogress.js',
        'vendor/laravel-admin/nestable/jquery.nestable.js',
        'vendor/laravel-admin/toastr/build/toastr.min.js',
        'vendor/laravel-admin/bootstrap3-editable/js/bootstrap-editable.min.js',
        'vendor/laravel-admin/sweetalert2/dist/sweetalert2.min.js',
        'vendor/laravel-admin/laravel-admin/laravel-admin.js',
    ];

    /**
     * @var string
     */
    public static $jQuery = 'vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js';

    /**
     * Add css or get all css.
     *
     * @param null $css
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function css($css = null)
    {
        if (!is_null($css)) {
            self::$css = array_merge(self::$css, (array) $css);

            return;
        }

        if ($css = static::getMinifiedCss()) {
            static::$css = [$css];
        } else {
            static::$css = array_merge(static::$css, static::baseCss(), (array) $css);
        }

        $css = array_filter(array_unique(static::$css));

        return view('admin::partials.css', compact('css'));
    }

    /**
     * @param null $css
     *
     * @return array|void
     */
    public static function baseCss($css = null)
    {
        if (!is_null($css)) {
            static::$baseCss = $css;

            return;
        }

        $skin = config('admin.skin', 'skin-blue-light');

        array_unshift(static::$baseCss, "vendor/laravel-admin/AdminLTE/dist/css/skins/{$skin}.min.css");

        return static::$baseCss;
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function js($js = null)
    {
        if (!is_null($js)) {
            self::$js = array_merge(self::$js, (array) $js);

            return;
        }

        if ($js = static::getMinifiedJs()) {
            static::$js = [$js];
        } else {
            static::$js = array_merge(static::baseJs(), static::$js, (array) $js);
        }

        $js = array_filter(array_unique(static::$js));

        return view('admin::partials.js', compact('js'));
    }

    /**
     * Add js or get all js.
     *
     * @param null $js
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function headerJs($js = null)
    {
        if (!is_null($js)) {
            self::$headerJs = array_merge(self::$headerJs, (array) $js);

            return;
        }

        static::$headerJs = array_merge(static::$headerJs, (array) $js);

        return view('admin::partials.js', ['js' => array_unique(static::$headerJs)]);
    }

    /**
     * @param null $js
     *
     * @return array|void
     */
    public static function baseJs($js = null)
    {
        if (!is_null($js)) {
            static::$baseJs = $js;

            return;
        }

        return static::$baseJs;
    }

    /**
     * @param string $script
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public static function script($script = '')
    {
        if (!empty($script)) {
            self::$script = array_merge(self::$script, (array) $script);

            return;
        }

        return view('admin::partials.script', ['script' => array_unique(self::$script)]);
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

        static::$manifestData = json_decode(file_get_contents(public_path(static::$manifest)), true);

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

        return static::getManifestData(Admin::$min['css']);
    }

    /**
     * @return bool|mixed
     */
    protected static function getMinifiedJs()
    {
        if (!config('admin.minify_assets') || !file_exists(public_path(static::$manifest))) {
            return false;
        }

        return static::getManifestData(Admin::$min['js']);
    }

    /**
     * @return string
     */
    public function jQuery()
    {
        return admin_asset(static::$jQuery);
    }
}
