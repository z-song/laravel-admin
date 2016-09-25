# 左侧边栏配置

左侧边栏的显示在文件`app/Admin/menu.php`中配置：

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menu items
    |--------------------------------------------------------------------------
    |
    | title:    item title.
    | url:      item url.
    | icon:     item icon, see https://fortawesome.github.io/Font-Awesome/icons/
    | children: subitems
    |
    */

    [
        'title' => 'Index',
        'url'   => '/',
        'icon'  => 'fa-bar-chart',
    ],
    [
        'title' => 'Auth',
        'icon'  => 'fa-tasks',
        'children' => [
                [
                    'title' => 'Users',
                    'url'   => 'auth/users',
                    'icon'  => 'fa-user',
                    'roles' => ['developer', 'administrator'],
                ],
                [
                    'title' => 'Roles',
                    'url'   => 'auth/roles',
                    'icon'  => 'fa-user',
                    'roles' => ['administrator'],
                ],
                [
                    'title' => 'Permissions',
                    'url'   => 'auth/permissions',
                    'icon'  => 'fa-user',
                    'roles' => ['administrator'],
                ],
            ]
    ],
];

```
`title`为显示标题。`url`为点击链接。`icon`为标题前图标,基于[font-awesome](https://fortawesome.github.io/Font-Awesome/icons/)的图标，`roles`可指定显示该菜单项的后台用户角色。如果有子级菜单项，可以在`children`中配置，配置内容和上面一样，侧边栏支持嵌套的多级菜单。
