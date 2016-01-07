<?php

return [

    'name'      => 'admin',

    'directory' => app_path('Admin2'),

    'prefix'    => 'admin',

    'auth'      => [
        'model'     => Encore\Admin\Auth\Database\Administrator::class
    ],

    'upload'    => [

        'image'     => public_path('upload/images'),

        'file'      => public_path('upload/files'),
    ]
];