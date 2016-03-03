# laravel-admin

`laravel-admin`是一个能帮助你快速搭建后台的工具，简单的几步操作，就能构建出功能丰富的后台。

`laravel-admin`基于以下开源工具或服务：

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Bootstrap Markdown](http://toopay.github.io/bootstrap-markdown/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Tencent map](http://lbs.qq.com/)

#Screenshot

![grid](https://cloud.githubusercontent.com/assets/1479100/12708148/6c4aa9fe-c8d7-11e5-94e4-c8105375a564.png)

![form](https://cloud.githubusercontent.com/assets/1479100/12708198/fc6725a8-c8d7-11e5-876f-5c4f00ded0ff.png)

# 安装

```
//laravel 5.1
composer require encore/laravel-admin "1.1.*"

//laravel 5.2
composer require encore/laravel-admin "1.2.*"
```

然后把`ServiceProvider`加入`config/app.php`中：

```
Encore\Admin\Providers\AdminServiceProvider::class
```

运行以下命令完成安装：

```
php artisan vendor:publish
php artisan admin:install
```

打开`http://localhost/admin/`访问后台，使用默认用户名`admin`和密码`admin`登陆。

#使用

默认安装目录为`app/Admin`。

该目录下的`routes.php`是路由文件，用来配置后台路由:

```php
<?php

$router = app('admin.router');

$router->get('/', function() {
    return view('admin::index');
});

$router->resources([
    'administrators' => AdministratorController::class
]);
```

`menu.php`用来配置左侧菜单：
```php
<?php

return [
    [
        'title' => '首页',
        'url'   => '/',
        'icon'  => 'fa-bar-chart'
    ],
    [
        'title' => '管理员',
        'url'   => '/administrators',
        'icon'  => 'fa-tasks'
    ],
];
```

`controllers`目录是控制器目录，用来存放控制器文件。

###创建控制器

如果要创建`User`模型的数据管理控制器，可使用以下命令：
```
php artisan admin:make UserController --model=\\App\\User
```

该命令会在`app\Admin\controllers`目录下面创建`UserController`文件，然后在routes.php加入：
```php
$router->resources([
    'users'           => UserController::class，  //加入这一行
    'administrators'  => AdministratorController::class
]);
```

最后在menu.php中加入访问入口：

```php
  [
    'title' => '用户管理',
    'url'   => '/users',
    'icon'  => 'fa-user'
  ],
```

然后就能在左侧菜单中看到`users`资源的访问链接了。

###Admin\Grid

`Admin\Grid`用来构建基于bootstarp table的数据列表，在控制器中：

```php
return Admin::grid(User::class, function(Grid $grid){

    $grid->id('ID')->sortable();

    //使用动态方法
    $grid->name('用户名');
    //或者使用column()方法：$grid->column('name', '用户名');
    
    //批量添加字段
    $grid->columns('email', 'username' ...);
    
    //关联模型数据
    $grid->column('profile.mobile', '手机');
    //或者使用：$grid->profile()->mobile('手机');
    
    //控制输出显示内容
    $grid->column('profile.mobile', '手机')->value(function($mobile) {
      return "+86 $mobile";
    });
    
    //使用sortable()添加可排序字段
    $grid->column('profile.age', '年龄')->sortable();
    
    $grid->created_at();
    $grid->updated_at();

    //设置查询条件: SELECT * FROM `user` WHERE id > 20 ORDER BY updated_at DESC;
    $grid->model()->where('id', '>', '20')->orderBy('updated_at', 'desc');

    //设置每页显示条数
    $grid->paginate(15);

    //设置action,show edit delete对应显示 编辑 删除
    $grid->actions('show|edit|delete');

    //添加行回调函数
    $grid->rows(function($row){
      if($row->id <= 10) {
        $row->style('color:red');
      }
    });
    
    //添加数据列表过滤器
    $grid->filter(function($filter){
    
        // sql: ... WHERE `user.name` LIKE "%$name%";
        $filter->like('name', '名字');
        
        // sql: ... WHERE `user.email` = $email;
        $filter->is('emial', '名字');
        
        // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
        $filter->between('created_at', '创建时间')->datetime();
    });
});

```

###Admin\Form

`Admin\Form`用来构建数据Form，在控制器中：

```php
return Admin::form(User::class, function(Form $form){

    $form->options(['title' => '用户修改']);
    
    $form->id('id', 'ID');
    $form->text('name', '用户名')->rules('required');
    $form->email('email', '邮箱')->rules('required|email');
    
    $form->password('password', '密码')->rules('required');
    
    // has one relation, user has one profile
    $form->url('profile.homepage', '个人主页');

    $form->ip('profile.last_login_ip', '上次登录ip');
    $form->datetime('profile.last_login_at', '上次登录时间');
    
    // 添加默认值
    $form->color('profile.color', '颜色')->default('#a34af4');

    $form->image('profile.avatar', '头像')/*->size(300, 300)*/;
    $form->file('profile.document', '文档')->rules('mimes:doc,docx,xlsx');
    $form->mobile('profile.mobile', '手机号');
    $form->text('profile.address', '地址');
    $form->date('profile.birthday', '生日');
    $form->radio('profile.gender', '性别')->values(['m' => '女', 'f'=> '男'])->default('m');

    // see http://lbs.qq.com/
    $form->map('profile.lat', 'profile.lng', '位置');
    
    // see http://ionden.com/a/plugins/ion.rangeSlider/en.html
    $form->slider('profile.age', '年龄')->options(['max' => 50, 'min' => 20, 'step' => 1, 'postfix' => '岁']);

    $form->datetime('created_at', '创建时间');
    $form->datetime('updated_at', '更新时间');

    $form->datetimeRange('profile.created_at', 'profile.updated_at', '时间线');

    // belongs to many relation
    $form->multipleSelect('friends', '好友')->options(User::all()->lists('name', 'id'));
    
    // belongs to many relation
    $form->checkbox('roles', '角色')->values(Role::all()->lists('display_name', 'id'));
    
    // 添加保存回调函数
    $form->saving(function($form) {
        $form->password = bcrypt($form->password);
    });
});
```

#License

[WTFPL](http://www.wtfpl.net/)
