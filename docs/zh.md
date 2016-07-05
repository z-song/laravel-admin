# laravel-admin

`laravel-admin` 是一个可以快速帮你构建后台管理的工具，使用很少的代码就实现功能完善的后台管理功能。


`laravel-admin` 基于以下组件或者服务:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Bootstrap Markdown](http://toopay.github.io/bootstrap-markdown/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [CodeMirror](https://codemirror.net/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/admin) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

#截图

![grid](https://cloud.githubusercontent.com/assets/1479100/12708148/6c4aa9fe-c8d7-11e5-94e4-c8105375a564.png)

![form](https://cloud.githubusercontent.com/assets/1479100/12708198/fc6725a8-c8d7-11e5-876f-5c4f00ded0ff.png)

# 安装

```
composer require encore/laravel-admin "dev-master"
```

在`config/app.php`加入`ServiceProvider`:

```
Encore\Admin\Providers\AdminServiceProvider::class
```

然后运行下面的命令完成安装：

```
php artisan vendor:publish
php artisan admin:install
```

最后在浏览器打开 `http://localhost/admin/` ,使用用户名 `admin` 和密码 `admin`登陆.

#使用

后台文件的默认安装地址在`app/Admin`，安装地址可以在`config/admin.php`中修改.

使用`app/Admin`目录下的`routes.php`来管理后台路由，使用方法和laravel框架的路由一样.

```php
<?php

$router = app('admin.router');

$router->get('/', function() {
    return view('admin::index');
});

$router->resources([
    'articles' => ArticleController::class
]);
```

`app/Admin`目录下的`menu.php`文件用来管理后台左侧边菜单：
```php
<?php

return [
    [
        'title' => 'Index',
        'url'   => '/',
        'icon'  => 'fa-bar-chart'
    ],
    [
        'title' => 'Administrators',
        'url'   => '/administrators',
        'icon'  => 'fa-tasks'
    ],
    [
        'title' => 'Multilevel',
        'icon'  => 'fa-circle-o',
        'children' => [
            [
                'title' => 'Level One',
                'url'   => '/',
                'icon'  => 'fa-circle-o'
            ],
            [
                'title' => 'Level One',
                'icon'  => 'fa-circle-o',
                'children' => [
                    [
                        'title' => 'Level Two',
                        'url'   => '/',
                        'icon'  => 'fa-circle-o'
                    ]
                ]
            ],
        ]
    ],
];
```

`app/Admin`目录下的`controllers/`目录用来存放后台路由器文件.

###使用示例

如果你想要创建一个基于`App/User`模型相关的管理界面，先创建一个路由器：
```
php artisan admin:make UserController --model=\\App\\User
```

上面的命令会在`app/Admin/controllers`创建控制器`UserController.php`

然后在`app/Admin/routes.php`文件添加路由配置：

```php
$router->resources([
    'users' => UserController::class，  //添加这一行
]);
```

最后在`app/Admin/menu.php`中加上入口

```php
  [
    'title' => 'Users',
    'url'   => '/users',
    'icon'  => 'fa-user'
  ],
```

然后在左侧边栏就能看到入口了。

打开`app/Admin/controllers/UserController.php`文件，里面已经默认包含了CURD相关方法，

###Admin\Grid

`Admin\Grid` is a data grid builder based on `bootstrap table`,in the controller:

```php
return Admin::grid(User::class, function(Grid $grid){

    $grid->id('ID')->sortable();

    //Use dynamic method.
    $grid->name();
    //or use column() method: $grid->column('name');

    //Add mulitiple columns.
    $grid->columns('email', 'username' ...);

    //Use related column (hasOne relation).
    $grid->column('profile.mobile', 'Mobile');
    //or use $grid->profile()->mobile('Mobile');

    //Use a callback function to display column value.
    $grid->column('profile.mobile', 'Mobile')->value(function($mobile) {
      return "+86 $mobile";
    });

    //Use sortable() method to make the column sortable.
    $grid->column('profile.age', 'Age')->sortable();
    
    // 
    $grid->column('progress')->progressBar(['danger', 'striped'], 'xs');

    //Wrapper value with a badge.
    $grid->created_at()->badge('danger');
    
    //Wrapper value with a label.
    $grid->updated_at()->label('success');

    //Set query conditions: SELECT * FROM `user` WHERE id > 20 ORDER BY updated_at DESC;
    $grid->model()->where('id', '>', '20')->orderBy('updated_at', 'desc');

    //Set 15 items per-page.
    $grid->paginate(15);

    //Set actions (edit,delete).
    $grid->actions('edit|delete');

    //Add row callback function.
    $grid->rows(function($row){
        if($row->id <= 10) {
            $row->style('color:red');
        }
      
        //Disable delete action for specify row.
        if($row->id % 3) {
            $row->action('edit');
        }
        
        //Add custom action for specify row.
        if($row->id % 2) {
            $row->actions()->add(function ($row) {
                return "<a class=\"btn btn-xs btn-danger\">btn</a>";
            });
        }
    });
    
    //Disable batch deletion.
    $grid->disableBatchDeletion();
    
    //Disable all actions.
    $grid->disableActions();

    //Add data grid filters.
    $grid->filter(function($filter){

        // sql: ... WHERE `user.name` LIKE "%$name%";
        $filter->like('name', 'name');

        // sql: ... WHERE `user.email` = $email;
        $filter->is('emial', 'Email');

        // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
        $filter->between('created_at', 'Created Time')->datetime();
    });
});

```

###Admin\Form

`Admin\Form` is a data form builder, in your controller：

```php
return Admin::form(User::class, function(Form $form){

    // $form->field(columnName [, columnName ], labelName = '');

    $form->display('id', 'ID');
    $form->text('name')->rules('required');
    $form->email('email')->rules('required|email');

    $form->password('password')->rules('required');

    //Related column (hasOne relation).
    $form->url('profile.homepage', 'Home page');

    $form->ip('last_login_ip', 'Last login ip');
    $form->datetime('last_login_at', 'Last login time');

    //All fields can set a default value.
    $form->color('color', 'Color')->default('#a34af4');

    //Code editor based on code mirror see https://codemirror.net/
    $form->code('code')->lang('ruby');
    $form->json('json');

    $form->currency('price')->symbol('￥');
    $form->number('count');
    $form->rate('rate');
    
    //You can use all Intervention Image api in image field (see http://image.intervention.io/getting_started/introduction)
    $form->image('avatar')->crop(int $width, int $height, [int $x, int $y]);
    
    $form->file('document')->rules('mimes:doc,docx,xlsx');
    $form->mobile('mobile')->format('999 9999 9999');
    $form->text('address');
    $form->date('birthday');
    $form->radio('gender')->values(['m' => 'Female', 'f'=> 'Male'])->default('m');

    //Use Google map or Tencent map.
    $form->map('latitude', 'longitude', 'Position');

    //Options see http://ionden.com/a/plugins/ion.rangeSlider/en.html.
    $form->slider('age', 'Age')->options(['max' => 50, 'min' => 20, 'step' => 1, 'postfix' => 'years old']);

    $form->display('created_at', 'Create time');
    $form->display('updated_at', 'Update time');

    $form->datetimeRange('created_at', 'profile.updated_at', 'Time line');

    //Belongs to many relation.
    $form->multipleSelect('friends')->options(User::all()->lists('name', 'id'));

    //Belongs to many relation.
    $form->checkbox('roles')->values(Role::all()->lists('display_name', 'id'));
    
    $form->switch('open')->states(['on' => 1, 'off' => 0]);
    
    //Add a divide line.
    $form->divide();

    //Has many relation, show as a list.
    $form->hasMany('comments', function(Grid $grid) {

        // Set resource path for items.
        $grid->resource('admin/article-comments');

        $grid->id('ID');
        $grid->author()->value(function($authorId){
            return User::find($authorId)->name;
        });
        $grid->email();
        $grid->content()->value(function($content) {
            return mb_strimwidth($content, 0, 40, '...');
        });
    });

    // Add saving callback function.
    $form->saving(function(Form $form) {
        if($form->password && $form->model()->password != $form->password)
        {
            $form->password = bcrypt($form->password);
        }
    });
});
```

#License

[WTFPL](http://www.wtfpl.net/)
