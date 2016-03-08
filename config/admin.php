<?php

return [

    'prefix'    => 'admin',

    'directory' => app_path('Admin'),

    'title'  => 'Admin',

    'auth' => [
        'guard'  => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
        'provider' => [
            'driver' => 'eloquent',
            'model' => \Encore\Admin\Auth\Database\Administrator::class,
        ],
    ],

    'upload'  => [
        'image'  => base_path('public/upload/image'),
        'file'   => base_path('public/upload/file'),
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