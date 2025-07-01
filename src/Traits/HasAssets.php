<?php

namespace Encore\Admin\Traits;

use Illuminate\Support\Facades\Vite;

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
     * @var bool
     */
    public static $viteEnabled = null;

    /**
     * @var array
     */
    public static $viteAssets = [
        'css' => ['resources/assets-vite/css/adminlte4.css'],
        'js' => ['resources/assets-vite/js/adminlte4.js'],
    ];

    /**
     * Check if Vite assets should be used
     *
     * @return bool
     */
    public static function useVite()
    {
        return config('admin.assets.use_vite', false) && 
               file_exists(public_path(config('admin.assets.vite_build_path', 'build') . '/manifest.json'));
    }

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
            ->map(function ($line) {
                return $line;
                //@see https://stackoverflow.com/questions/19509863/how-to-remove-js-comments-using-php
                $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
                $line = preg_replace($pattern, '', $line);

                return preg_replace('/\s+/', ' ', $line);
            });

        return view('admin::partials.script', compact('script'));
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
     * @return string
     */
    public function jQuery()
    {
        return admin_asset(static::$jQuery);
    }

    /**
     * @param $component
     */
    public static function component($component, $data = [])
    {
        $string = view($component, $data)->render();

        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$string);
        libxml_use_internal_errors(false);

        if ($head = $dom->getElementsByTagName('head')->item(0)) {
            foreach ($head->childNodes as $child) {
                if ($child instanceof \DOMElement) {
                    if ($child->tagName == 'style' && !empty($child->nodeValue)) {
                        static::style($child->nodeValue);
                        continue;
                    }

                    if ($child->tagName == 'link' && $child->hasAttribute('href')) {
                        static::css($child->getAttribute('href'));
                    }

                    if ($child->tagName == 'script') {
                        if ($child->hasAttribute('src')) {
                            static::js($child->getAttribute('src'));
                        } else {
                            static::script(';(function () {'.$child->nodeValue.'})();');
                        }

                        continue;
                    }
                }
            }
        }

        $render = '';

        if ($body = $dom->getElementsByTagName('body')->item(0)) {
            foreach ($body->childNodes as $child) {
                if ($child instanceof \DOMElement) {
                    if ($child->tagName == 'style' && !empty($child->nodeValue)) {
                        static::style($child->nodeValue);
                        continue;
                    }

                    if ($child->tagName == 'script' && !empty($child->nodeValue)) {
                        static::script(';(function () {'.$child->nodeValue.'})();');
                        continue;
                    }

                    if ($child->tagName == 'template') {
                        $html = '';
                        foreach ($child->childNodes as $childNode) {
                            $html .= $child->ownerDocument->saveHTML($childNode);
                        }
                        $html && static::html($html);
                        continue;
                    }
                }

                $render .= $body->ownerDocument->saveHTML($child);
            }
        }

        return trim($render);
    }

    /**
     * Check if Vite assets should be used
     *
     * @return bool
     */
    public static function isViteEnabled()
    {
        if (static::$viteEnabled === null) {
            static::$viteEnabled = config('admin.vite.enabled', true) && 
                                   config('admin.ui_framework') === 'adminlte4';
        }

        return static::$viteEnabled;
    }

    /**
     * Get Vite CSS assets
     *
     * @return string
     */
    public static function viteCSS()
    {
        if (!static::isViteEnabled()) {
            return '';
        }

        try {
            return Vite::useBuildDirectory(config('admin.vite.build_path', 'build'))
                       ->withEntryPoints(static::$viteAssets['css'])
                       ->toHtml();
        } catch (\Exception $e) {
            // Fallback to legacy assets if Vite fails
            return '';
        }
    }

    /**
     * Get Vite JS assets
     *
     * @return string
     */
    public static function viteJS()
    {
        if (!static::isViteEnabled()) {
            return '';
        }

        try {
            return Vite::useBuildDirectory(config('admin.vite.build_path', 'build'))
                       ->withEntryPoints(static::$viteAssets['js'])
                       ->toHtml();
        } catch (\Exception $e) {
            // Fallback to legacy assets if Vite fails
            return '';
        }
    }

    /**
     * Initialize AdminLTE 4 JavaScript
     *
     * @return string
     */
    public static function adminLTE4Init()
    {
        $config = [
            'framework' => config('admin.ui_framework', 'adminlte4'),
            'theme' => config('admin.theme', 'light'),
            'themeColor' => config('admin.theme_color', 'blue'),
            'layout' => config('admin.layout', []),
            'viteEnabled' => static::isViteEnabled(),
            'locale' => config('app.locale', 'en'),
        ];

        $script = "
        // AdminLTE 4 + Bootstrap 5 Initialization
        window.AdminConfig = " . json_encode($config) . ";
        
        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set theme attributes
            document.documentElement.setAttribute('data-bs-theme', AdminConfig.theme);
            document.body.setAttribute('data-admin-theme-color', AdminConfig.themeColor);
            
            // Initialize layout options
            if (AdminConfig.layout.sidebar_mini) {
                document.body.classList.add('sidebar-mini');
            }
            if (AdminConfig.layout.sidebar_collapse) {
                document.body.classList.add('sidebar-collapse');
            }
            if (AdminConfig.layout.navbar_fixed) {
                document.body.classList.add('navbar-fixed');
            }
            if (AdminConfig.layout.footer_fixed) {
                document.body.classList.add('footer-fixed');
            }
            
            // Initialize Bootstrap components if available
            if (typeof bootstrap !== 'undefined') {
                // Initialize tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                
                // Initialize popovers
                const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"popover\"]'));
                popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            }
            
            // Back to top button functionality
            const backToTop = document.getElementById('totop');
            if (backToTop) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTop.style.display = 'block';
                    } else {
                        backToTop.style.display = 'none';
                    }
                });
                
                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
            
            // Theme persistence
            const savedTheme = localStorage.getItem('admin-theme');
            if (savedTheme && savedTheme !== AdminConfig.theme) {
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
                AdminConfig.theme = savedTheme;
            }
        });
        
        // Theme toggle functionality
        window.toggleTheme = function() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('admin-theme', newTheme);
            AdminConfig.theme = newTheme;
            
            // Update toggle button icon
            const toggleIcon = document.querySelector('.dark-mode-toggle i');
            if (toggleIcon) {
                toggleIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
            
            // Dispatch theme change event
            window.dispatchEvent(new CustomEvent('admin:theme-changed', {
                detail: { theme: newTheme }
            }));
        };
        
        // Legacy jQuery compatibility
        if (typeof $ !== 'undefined') {
            // Ensure jQuery events work with Bootstrap 5
            $(document).ready(function() {
                // Legacy AdminLTE compatibility
                if (typeof AdminLTE !== 'undefined') {
                    console.warn('AdminLTE 2/3 detected. Consider upgrading to AdminLTE 4 for better compatibility.');
                }
                
                // Legacy event handlers
                $('.sidebar-toggle').on('click', function(e) {
                    e.preventDefault();
                    if (typeof window.AdminLTE4Layout !== 'undefined') {
                        // Use new AdminLTE 4 layout if available
                        const layout = new window.AdminLTE4Layout();
                        layout.toggleSidebar();
                    }
                });
            });
        }";

        return $script;
    }

    /**
     * Get Bootstrap 5 compatibility script
     *
     * @return string
     */
    public static function bootstrap5Compatibility()
    {
        return "
        // Bootstrap 5 Compatibility Layer
        (function() {
            // Update data attributes for Bootstrap 5
            document.addEventListener('DOMContentLoaded', function() {
                // Convert old data-toggle to data-bs-toggle
                const elementsWithToggle = document.querySelectorAll('[data-toggle]');
                elementsWithToggle.forEach(function(el) {
                    const toggleValue = el.getAttribute('data-toggle');
                    el.setAttribute('data-bs-toggle', toggleValue);
                    el.removeAttribute('data-toggle');
                });
                
                // Convert old data-target to data-bs-target
                const elementsWithTarget = document.querySelectorAll('[data-target]');
                elementsWithTarget.forEach(function(el) {
                    const targetValue = el.getAttribute('data-target');
                    el.setAttribute('data-bs-target', targetValue);
                    el.removeAttribute('data-target');
                });
                
                // Convert old data-dismiss to data-bs-dismiss
                const elementsWithDismiss = document.querySelectorAll('[data-dismiss]');
                elementsWithDismiss.forEach(function(el) {
                    const dismissValue = el.getAttribute('data-dismiss');
                    el.setAttribute('data-bs-dismiss', dismissValue);
                    el.removeAttribute('data-dismiss');
                });
            });
        })();";
    }
}
