<?php

return [

    'name'  => 'Laravel-admin',

    'prefix'    => 'admin',

    'directory' => app_path('Admin'),

    'title'  => 'Admin',

    'auth' => [
        'driver'   => 'session',
        'provider' => '',
        'model'    => Encore\Admin\Auth\Database\Administrator::class,
    ],

    'upload'  => [

        'disk' => 'admin',

        'directory'  => [
            'image'  => 'image',
            'file'   => 'file',
        ],

        'host' => 'http://localhost:8000/upload/',
    ],

    'database' => [
        'users_table' => 'admin_users',
        'users_model' => Encore\Admin\Auth\Database\Administrator::class,

        'roles_table' => 'admin_roles',
        'roles_model' => Encore\Admin\Auth\Database\Role::class,

        'permissions_table' => 'admin_permissions',
        'permissions_model' => Encore\Admin\Auth\Database\Permission::class,

        'menu_table'  => 'admin_menu',
        'menu_model'  => Encore\Admin\Auth\Database\Menu::class,

        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
    ],

    /*
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
     */
    'skin'    => 'skin-blue',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout'  => ['sidebar-mini'],

    'version'   => '1.0',
];
