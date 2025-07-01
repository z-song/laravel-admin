<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin name
    |--------------------------------------------------------------------------
    |
    | This value is the name of laravel-admin, This setting is displayed on the
    | login page.
    |
    */
    'name' => 'Laravel-admin',

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages. You can also set it as an image by using a
    | `img` tag, eg '<img src="http://logo-url" alt="Admin logo">'.
    |
    */
    'logo' => '<b>Laravel</b> admin',

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin mini logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages when the sidebar menu is collapsed. You can
    | also set it as an image by using a `img` tag, eg
    | '<img src="http://logo-url" alt="Admin logo">'.
    |
    */
    'logo-mini' => '<b>La</b>',

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin bootstrap setting
    |--------------------------------------------------------------------------
    |
    | This value is the path of laravel-admin bootstrap file.
    |
    */
    'bootstrap' => app_path('Admin/bootstrap.php'),

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),

        'namespace' => 'App\\Admin\\Controllers',

        'middleware' => ['web', 'admin'],

        // Laravel 11 API integration support
        'api' => [
            'enable' => env('ADMIN_API_ENABLE', false),
            'prefix' => env('ADMIN_API_PREFIX', 'admin-api'),
            'middleware' => ['api', 'admin.auth:sanctum'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin asset settings
    |--------------------------------------------------------------------------
    |
    | Asset management configuration for modern build tools and legacy support.
    | Laravel 11 Vite integration with backward compatibility.
    |
    */
    'assets' => [
        // Enable Vite for modern asset compilation (requires npm run build)
        'use_vite' => env('ADMIN_USE_VITE', false),
        
        // Vite build output directory (relative to public/)
        'vite_build_path' => env('ADMIN_VITE_BUILD_PATH', 'build'),
        
        // Legacy asset fallback (maintains backward compatibility)
        'legacy_fallback' => env('ADMIN_LEGACY_FALLBACK', true),
        
        // Asset versioning for cache busting
        'version' => env('ADMIN_ASSET_VERSION', '1.0.0'),
        
        // CDN configuration for external libraries
        'cdn' => [
            'enable' => env('ADMIN_CDN_ENABLE', false),
            'jquery' => 'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
            'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => app_path('Admin'),

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin html title
    |--------------------------------------------------------------------------
    |
    | Html title for all pages.
    |
    */
    'title' => 'Admin',

    /*
    |--------------------------------------------------------------------------
    | Access via `https`
    |--------------------------------------------------------------------------
    |
    | If your page is going to be accessed via https, set it to `true`.
    |
    */
    'https' => env('ADMIN_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all admin pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [

        'controller' => App\Admin\Controllers\AuthController::class,

        'guard' => 'admin',

        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => Encore\Admin\Auth\Database\Administrator::class,
            ],
        ],

        // Add "remember me" to login form
        'remember' => true,

        // Redirect to the specified URI when user is not authorized.
        'redirect_to' => 'auth/login',

        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin upload setting
    |--------------------------------------------------------------------------
    |
    | File system configuration for form upload files and images, including
    | disk and upload path.
    |
    */
    'upload' => [

        // Disk in `config/filesystem.php`.
        'disk' => 'admin',

        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for laravel-admin builtin model & tables.
    |
    */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => Encore\Admin\Auth\Database\Administrator::class,

        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => Encore\Admin\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'admin_permissions',
        'permissions_model' => Encore\Admin\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Encore\Admin\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
    ],

    /*
    |--------------------------------------------------------------------------
    | User operation log setting
    |--------------------------------------------------------------------------
    |
    | By setting this option to open or close operation log in laravel-admin.
    |
    */
    'operation_log' => [

        'enable' => true,

        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs.
         */
        'except' => [
            env('ADMIN_ROUTE_PREFIX', 'admin').'/auth/logs*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check route permission.
    |--------------------------------------------------------------------------
    */
    'check_route_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check menu roles.
    |--------------------------------------------------------------------------
    */
    'check_menu_roles'       => true,

    /*
    |--------------------------------------------------------------------------
    | User default avatar
    |--------------------------------------------------------------------------
    |
    | Set a default avatar for newly created users.
    |
    */
    'default_avatar' => '/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg',

    /*
    |--------------------------------------------------------------------------
    | Admin map field provider
    |--------------------------------------------------------------------------
    |
    | Supported: "tencent", "google", "yandex".
    |
    */
    'map_provider' => 'google',

    /*
    |--------------------------------------------------------------------------
    | Application Skin
    |--------------------------------------------------------------------------
    |
    | This value is the skin of admin pages.
    | @see https://adminlte.io/docs/2.4/layout
    |
    | Supported:
    |    "skin-blue", "skin-blue-light", "skin-yellow", "skin-yellow-light",
    |    "skin-green", "skin-green-light", "skin-purple", "skin-purple-light",
    |    "skin-red", "skin-red-light", "skin-black", "skin-black-light".
    |
    */
    'skin' => env('ADMIN_SKIN', 'skin-blue-light'),

    /*
    |--------------------------------------------------------------------------
    | Application layout
    |--------------------------------------------------------------------------
    |
    | This value is the layout of admin pages.
    | @see https://adminlte.io/docs/2.4/layout
    |
    | Supported: "fixed", "layout-boxed", "layout-top-nav", "sidebar-collapse",
    | "sidebar-mini".
    |
    */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
    |--------------------------------------------------------------------------
    | Login page background image
    |--------------------------------------------------------------------------
    |
    | This value is used to set the background image of login page.
    |
    */
    'login_background_image' => '',

    /*
    |--------------------------------------------------------------------------
    | Show version at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the version number of laravel-admin at the footer of
    | each page
    |
    */
    'show_version' => true,

    /*
    |--------------------------------------------------------------------------
    | Show environment at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the environment at the footer of each page
    |
    */
    'show_environment' => true,

    /*
    |--------------------------------------------------------------------------
    | Menu bind to permission
    |--------------------------------------------------------------------------
    |
    | whether enable menu bind to a permission
    */
    'menu_bind_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable default breadcrumb
    |--------------------------------------------------------------------------
    |
    | Whether enable default breadcrumb for every page content.
    */
    'enable_default_breadcrumb' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable assets minify
    |--------------------------------------------------------------------------
    */
    'minify_assets' => [

        // Assets will not be minified.
        'excepts' => [

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable sidebar menu search
    |--------------------------------------------------------------------------
    */
    'enable_menu_search' => true,

    /*
    |--------------------------------------------------------------------------
    | Exclude route from generate menu command
    |--------------------------------------------------------------------------
    */
    'menu_exclude' => [
        '_handle_selectable_',
        '_handle_renderable_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert message that will displayed on top of the page.
    |--------------------------------------------------------------------------
    */
    'top_alert' => '',

    /*
    |--------------------------------------------------------------------------
    | The global Grid action display class.
    |--------------------------------------------------------------------------
    */
    'grid_action_class' => \Encore\Admin\Grid\Displayers\DropdownActions::class,

    /*
    |--------------------------------------------------------------------------
    | Extension Directory
    |--------------------------------------------------------------------------
    |
    | When you use command `php artisan admin:extend` to generate extensions,
    | the extension files will be generated in this directory.
    */
    'extension_dir' => app_path('Admin/Extensions'),

    /*
    |--------------------------------------------------------------------------
    | Settings for extensions.
    |--------------------------------------------------------------------------
    |
    | You can find all available extensions here
    | https://github.com/laravel-admin-extensions.
    |
    */
    'extensions' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel 11 Real-time Features (Laravel Reverb)
    |--------------------------------------------------------------------------
    |
    | Configuration for Laravel Reverb WebSocket server integration.
    | Enable real-time features like live notifications, data updates,
    | and collaborative editing in admin panels.
    |
    */
    'reverb' => [
        'enabled' => env('ADMIN_REVERB_ENABLED', false),
        'app_id' => env('REVERB_APP_ID'),
        'key' => env('REVERB_APP_KEY'),
        'secret' => env('REVERB_APP_SECRET'),
        'host' => env('REVERB_HOST', '127.0.0.1'),
        'port' => env('REVERB_PORT', 8080),
        'scheme' => env('REVERB_SCHEME', 'http'),
        
        // Real-time features configuration
        'features' => [
            'notifications' => env('ADMIN_REALTIME_NOTIFICATIONS', true),
            'grid_updates' => env('ADMIN_REALTIME_GRID_UPDATES', true),
            'user_presence' => env('ADMIN_REALTIME_USER_PRESENCE', true),
            'operation_log' => env('ADMIN_REALTIME_OPERATION_LOG', true),
        ],
        
        // Channel configuration
        'channels' => [
            'admin_notifications' => 'admin.notifications',
            'admin_operations' => 'admin.operations.{user_id}',
            'admin_presence' => 'admin.presence',
            'grid_updates' => 'admin.grid.{model}',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel 11 Performance Optimization
    |--------------------------------------------------------------------------
    |
    | Configuration for Laravel 11 performance features including
    | caching strategies, queue integration, and optimization settings.
    |
    */
    'performance' => [
        // Cache configuration for admin data
        'cache' => [
            'enabled' => env('ADMIN_CACHE_ENABLED', true),
            'driver' => env('ADMIN_CACHE_DRIVER', 'redis'),
            'prefix' => env('ADMIN_CACHE_PREFIX', 'admin'),
            'ttl' => env('ADMIN_CACHE_TTL', 3600), // 1 hour
            
            // Specific cache settings
            'menu_cache' => env('ADMIN_MENU_CACHE', true),
            'permission_cache' => env('ADMIN_PERMISSION_CACHE', true),
            'role_cache' => env('ADMIN_ROLE_CACHE', true),
        ],
        
        // Queue configuration for background processing
        'queue' => [
            'enabled' => env('ADMIN_QUEUE_ENABLED', false),
            'connection' => env('ADMIN_QUEUE_CONNECTION', 'redis'),
            'queue' => env('ADMIN_QUEUE_NAME', 'admin'),
            
            // Operations that should be queued
            'operations' => [
                'export' => true,
                'import' => true,
                'bulk_operations' => true,
                'file_processing' => true,
            ],
        ],
        
        // Database optimization
        'database' => [
            'eager_loading' => env('ADMIN_EAGER_LOADING', true),
            'chunk_size' => env('ADMIN_CHUNK_SIZE', 1000),
            'query_cache' => env('ADMIN_QUERY_CACHE', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel 11 API Integration
    |--------------------------------------------------------------------------
    |
    | Enhanced API configuration with Laravel 11 features including
    | Sanctum integration, rate limiting, and API versioning.
    |
    */
    'api' => [
        'enabled' => env('ADMIN_API_ENABLED', false),
        'prefix' => env('ADMIN_API_PREFIX', 'admin-api'),
        'version' => env('ADMIN_API_VERSION', 'v1'),
        
        // Authentication
        'auth' => [
            'driver' => env('ADMIN_API_AUTH_DRIVER', 'sanctum'),
            'token_expiry' => env('ADMIN_API_TOKEN_EXPIRY', 1440), // 24 hours
        ],
        
        // Rate limiting (Laravel 11 per-second rate limiting)
        'rate_limiting' => [
            'enabled' => env('ADMIN_API_RATE_LIMIT_ENABLED', true),
            'per_minute' => env('ADMIN_API_RATE_LIMIT_PER_MINUTE', 60),
            'per_second' => env('ADMIN_API_RATE_LIMIT_PER_SECOND', 2),
        ],
        
        // API features
        'features' => [
            'crud_operations' => env('ADMIN_API_CRUD', true),
            'file_upload' => env('ADMIN_API_FILE_UPLOAD', true),
            'batch_operations' => env('ADMIN_API_BATCH_OPS', true),
            'search' => env('ADMIN_API_SEARCH', true),
        ],
    ],
];
