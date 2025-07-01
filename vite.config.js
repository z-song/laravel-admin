import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets-vite/css/app.css',
                'resources/assets-vite/css/adminlte4.css',
                'resources/assets-vite/js/app.js',
                'resources/assets-vite/js/adminlte4.js'
            ],
            refresh: true,
            publicDirectory: 'public/vendor/laravel-admin',
        }),
    ],
    
    build: {
        outDir: 'public/vendor/laravel-admin/build',
        manifest: true,
        emptyOutDir: false, // Don't empty the directory to preserve legacy assets
        
        rollupOptions: {
            output: {
                // Asset naming for cache busting
                assetFileNames: (assetInfo) => {
                    let extType = assetInfo.name.split('.').at(1);
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'img';
                    }
                    return `${extType}/[name]-[hash][extname]`;
                },
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
            },
            
            // External dependencies (load from CDN or keep as legacy)
            external: (id) => {
                // Let jQuery be handled by legacy system for backward compatibility
                return id === 'jquery';
            }
        },
        
        // CSS code splitting
        cssCodeSplit: true,
        
        // Minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
        },
        
        // Source maps for development
        sourcemap: process.env.NODE_ENV === 'development',
        
        // Target modern browsers but maintain some compatibility
        target: ['es2020', 'chrome64', 'firefox67', 'safari12'],
        
        // Asset size warnings
        chunkSizeWarningLimit: 1000,
    },
    
    // Development server configuration
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        
        // HMR configuration
        hmr: {
            host: 'localhost',
        },
        
        // Proxy configuration for development
        proxy: {
            // Proxy admin requests to Laravel dev server
            '/admin': {
                target: 'http://localhost:8000',
                changeOrigin: true,
            },
        },
    },
    
    // CSS preprocessing
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "resources/assets-vite/css/base/variables.css";`
            }
        },
        
        // PostCSS configuration
        postcss: {
            plugins: [
                // Autoprefixer for vendor prefixes
                autoprefixer({
                    overrideBrowserslist: [
                        '> 1%',
                        'last 2 versions',
                        'Firefox ESR',
                        'not dead'
                    ],
                }),
                
                // CSS Nano for production minification
                ...(process.env.NODE_ENV === 'production' ? [
                    cssnano({
                        preset: ['default', {
                            discardComments: {
                                removeAll: true,
                            },
                        }],
                    })
                ] : []),
            ],
        },
        
        // CSS modules (disabled for admin to maintain compatibility)
        modules: false,
    },
    
    // Resolve configuration
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/assets-vite'),
            '@components': resolve(__dirname, 'resources/assets-vite/js/components'),
            '@legacy': resolve(__dirname, 'resources/assets-vite/js/legacy'),
            '@css': resolve(__dirname, 'resources/assets-vite/css'),
        },
    },
    
    // Dependency optimization
    optimizeDeps: {
        include: [
            // Include heavy dependencies for pre-bundling
        ],
        exclude: [
            // Exclude legacy jQuery from optimization
            'jquery',
        ],
    },
    
    // Environment variables
    define: {
        __ADMIN_VERSION__: JSON.stringify(process.env.npm_package_version || '2.0.0'),
        __VITE_MODE__: JSON.stringify(process.env.NODE_ENV),
    },
    
    // Legacy browser support (if needed)
    legacy: {
        targets: ['defaults', 'not IE 11'],
        additionalLegacyPolyfills: ['regenerator-runtime/runtime'],
    },
});