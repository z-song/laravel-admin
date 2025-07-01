<?php

namespace Encore\Admin\Traits;

use Illuminate\Support\Facades\Vite;

/**
 * Laravel 11 Vite Integration Support for Laravel-admin
 * 
 * This trait provides dual asset management system supporting both:
 * - Legacy asset system (current HasAssets trait)
 * - Modern Vite-based asset compilation
 * 
 * Usage: Set ADMIN_USE_VITE=true in .env to enable Vite mode
 */
trait HasViteAssets
{
    /**
     * Check if Vite should be used for asset management
     *
     * @return bool
     */
    public static function useVite(): bool
    {
        return config('admin.assets.use_vite', false) && 
               file_exists(public_path(config('admin.assets.vite_build_path', 'build') . '/manifest.json'));
    }

    /**
     * Get CSS assets using Vite or legacy system
     *
     * @param null $css
     * @param bool $minify
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function viteAwareCss($css = null, $minify = true)
    {
        if (static::useVite()) {
            return static::viteCss($css);
        }
        
        // Fallback to legacy CSS method
        return static::css($css, $minify);
    }

    /**
     * Get JS assets using Vite or legacy system
     *
     * @param null $js
     * @param bool $minify
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function viteAwareJs($js = null, $minify = true)
    {
        if (static::useVite()) {
            return static::viteJs($js);
        }
        
        // Fallback to legacy JS method
        return static::js($js, $minify);
    }

    /**
     * Handle CSS through Vite
     *
     * @param null $css
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected static function viteCss($css = null)
    {
        if (!is_null($css)) {
            static::$css = array_merge(static::$css, (array) $css);
        }

        // Get Vite-processed CSS
        $viteCss = static::getViteCssAssets();
        
        // Merge with additional CSS
        $css = array_merge($viteCss, static::$css);
        $css = array_filter(array_unique($css));

        return view('admin::partials.css', compact('css'));
    }

    /**
     * Handle JS through Vite
     *
     * @param null $js
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected static function viteJs($js = null)
    {
        if (!is_null($js)) {
            static::$js = array_merge(static::$js, (array) $js);
        }

        // Get Vite-processed JS
        $viteJs = static::getViteJsAssets();
        
        // Merge with additional JS
        $js = array_merge($viteJs, static::$js);
        $js = array_filter(array_unique($js));

        return view('admin::partials.js', compact('js'));
    }

    /**
     * Get Vite CSS assets
     *
     * @return array
     */
    protected static function getViteCssAssets(): array
    {
        try {
            // Use Laravel's Vite helper to get CSS files
            $viteAssets = Vite::asset('resources/assets-vite/css/app.css');
            return [$viteAssets];
        } catch (\Exception $e) {
            // Fallback to legacy CSS if Vite fails
            return static::baseCss();
        }
    }

    /**
     * Get Vite JS assets
     *
     * @return array
     */
    protected static function getViteJsAssets(): array
    {
        try {
            // Use Laravel's Vite helper to get JS files
            $viteAssets = Vite::asset('resources/assets-vite/js/app.js');
            return [
                static::$jQuery, // Keep jQuery for compatibility
                $viteAssets
            ];
        } catch (\Exception $e) {
            // Fallback to legacy JS if Vite fails
            return static::baseJs();
        }
    }

    /**
     * Generate Vite development server tag for HMR
     * 
     * @return string
     */
    public static function viteHmrTags(): string
    {
        if (app()->environment('local') && static::useVite()) {
            try {
                return Vite::useBuildDirectory(config('admin.assets.vite_build_path', 'build'))
                    ->withEntryPoints([
                        'resources/assets-vite/css/app.css',
                        'resources/assets-vite/js/app.js'
                    ]);
            } catch (\Exception $e) {
                return '';
            }
        }
        
        return '';
    }

    /**
     * Check if we're in Vite development mode
     *
     * @return bool
     */
    public static function isViteDev(): bool
    {
        return static::useVite() && 
               app()->environment('local') && 
               static::viteDevServerRunning();
    }

    /**
     * Check if Vite development server is running
     *
     * @return bool
     */
    protected static function viteDevServerRunning(): bool
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 1,
                    'method' => 'GET'
                ]
            ]);
            
            $result = @file_get_contents('http://localhost:5173', false, $context);
            return $result !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}