<?php

return [

    /*
    |--------------------------------------------------------------------------
    | The name of admin application
    |--------------------------------------------------------------------------
    |
    | This value is the name of admin application, This setting is displayed on the
    | login page.
    |
    */
    'name' => 'Laravel-admin',

    /*
    |--------------------------------------------------------------------------
    | Logo setting of admin application
    |--------------------------------------------------------------------------
    |
    */
    'logo' => [

        'image' => '/vendor/laravel-admin/AdminLTE/img/AdminLTELogo.png',

        'text' => '<span class="font-weight-bolder">Laravel-admin</span>',
    ],

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

        'as' => 'admin.',
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
                'model'  => Encore\Admin\Models\Administrator::class,
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
    | Single device login / 单设备登录
    |--------------------------------------------------------------------------
    |
    | Invalidating and "logging out" a user's sessions that are active on other
    | devices without invalidating the session on their current device.
    |
    */
    'single_device_login' => false,

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
        'users_model' => Encore\Admin\Models\Administrator::class,

        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Encore\Admin\Models\Menu::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | User default avatar
    |--------------------------------------------------------------------------
    |
    | Set a default avatar for newly created users.
    |
    */
    'default_avatar' => '/vendor/laravel-admin/AdminLTE/img/user2-160x160.jpg',

    /*
    |--------------------------------------------------------------------------
    | Application theme
    |--------------------------------------------------------------------------
    |
    | @see https://adminlte.io/docs/3.0/layout.html
    |
    */
    'theme' => [

        /*
        |--------------------------------------------------------------------------
        | Available layout options.
        |--------------------------------------------------------------------------
        | Fixed Sidebar: use the class `.layout-fixed` to get a fixed sidebar.
        | Fixed Navbar: use the class `.layout-navbar-fixed` to get a fixed navbar.
        | Fixed Footer: use the class `.layout-footer-fixed` to get a fixed footer.
        | Collapsed Sidebar: use the class `.sidebar-collapse` to have a collapsed sidebar upon loading.
        | Boxed Layout: use the class `.layout-boxed` to get a boxed layout that stretches only to 1250px.
        | Top Navigation: use the class `.layout-top-nav` to remove the sidebar and have your links at the top navbar.
        |
        */
        'layout' => ['sidebar-mini', 'sidebar-collapse', 'text-sm'],

        /*
        |--------------------------------------------------------------------------
        | Default color for all links.
        |--------------------------------------------------------------------------
        |
        | navbar-light or navbar-dark for content color
        |
        | navbar-$color for backgroud color
        |
        | Available $color options:
        |    primary secondary secondary info warning danger black gray-dark  gray
        |    light indigo  navy purple fuchsia pink maroon orange lime teal olive
        |
        */
        'navbar' => 'navbar-light navbar-white',

        /*
        |--------------------------------------------------------------------------
        | Default color for all links.
        |--------------------------------------------------------------------------
        |
        | Available options:
        |    primary secondary secondary info warning danger black gray-dark  gray
        |    light indigo  navy purple fuchsia pink maroon orange lime teal olive
        |
        */
        'accent' => 'info',

        /*
        |--------------------------------------------------------------------------
        | Default color for card form and buttons.
        |--------------------------------------------------------------------------
        |
        | light-$color or dark-$color
        |
        | Available $color options:
        |    primary secondary secondary info warning danger black gray-dark  gray
        |    light indigo  navy purple fuchsia pink maroon orange lime teal olive
        |
        */
        'sidebar' => 'light-info',

        /*
        |--------------------------------------------------------------------------
        | Default color for card, form and buttons.
        |--------------------------------------------------------------------------
        |
        | Available options:
        |    primary secondary secondary info warning danger
        */
        'color' => 'info',

        /*
        |--------------------------------------------------------------------------
        | Logo backgroud color.
        |--------------------------------------------------------------------------
        |
        | Available color options:
        |    primary secondary secondary info warning danger black gray-dark  gray
        |    light indigo  navy purple fuchsia pink maroon orange lime teal olive
        |
        */
        'logo' => 'light',
    ],

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
    | Alert message that will displayed on top of the page.
    |--------------------------------------------------------------------------
    */
    'top_alert' => '',

    /*
    |--------------------------------------------------------------------------
    | The global Table action display class.
    |--------------------------------------------------------------------------
    */
    'table_action_class' => \Encore\Admin\Table\Displayers\DropdownActions::class,

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
];
