<?php

return [

    'prefix'    => 'admin',

    'directory' => app_path('Admin'),

    'title'  => 'Admin',

    'auth' => [
        'driver'   => 'session',
        'provider' => '',
        'model'    => Encore\Admin\Auth\Database\Administrator::class,
    ],

    'upload'  => [
        'image'  => base_path('public/upload/image'),
        'file'   => base_path('public/upload/file'),
    ],

    'database' => [
        'users_table' => 'administrators',
        'users_model' => Encore\Admin\Auth\Database\Administrator::class,

        'roles_table' => 'roles',
        'roles_model' => Encore\Admin\Auth\Database\Role::class,

        'permissions_table' => 'permissions',
        'permissions_model' => Encore\Admin\Auth\Database\Permission::class,

        'role_users_table'       => 'role_administrators',
        'role_permissions_table' => 'role_permissions',
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
    'skin'    => 'skin-green',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout'  => [],
];
