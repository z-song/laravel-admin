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

![grid](https://cloud.githubusercontent.com/assets/1479100/16609399/894e0832-4386-11e6-8709-1cc7ce429e7c.png)

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
php artisan vendor:publish --tag=laravel-admin
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
        'title' => 'Multilevel',
        'icon'  => 'fa-circle-o',
        'children' => [
            [
                'title' => 'Level One',
                'url'   => '/',
                'icon'  => 'fa-circle-o'
            ],
            ...
        ]
    ],
];
```

`app/Admin`目录下的`controllers/`目录用来存放后台路由器文件.

##使用示例

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

打开`app/Admin/controllers/UserController.php`文件，里面已经默认包含了CURD相关方法，`index()`显示列表页，`create()`用来显示创建页，`edit()`是编辑页，另外的两个方法`grid()`用来创建数据表格，`form()`用来创建form表单，我们的主要工作就是在`grid()`和`form()`两个方法中编写穿件数据表格和form表单的代码。

###创建数据表格

在`grid()`方法中使用`Encore\Admin\Admin::grid()`方法来创建数据表格容器，数据表格的内容都是在容器中定义的

```
use App\User;

protected function grid()
{
    return Admin::grid(User::class, function (Grid $grid) {

        $grid->id('ID')->sortable();
        $grid->name();
        $grid->email();

        $grid->created_at();
        $grid->updated_at();
    });
}

```

使用`$grid->{字段名}([$label]);`为数据表格添加一列数据，该列数据使用`User`模型中`{字段名}`的数据填充。

也可以使用以下方式添加数据列：

```

//添加一列
$grid->column('字段名', '列title');

//添加多列
$grid->columns('email', 'username' ...);
```

如果需要改变该列数据的显示内容，可以使用以下方式:

```
$grid->name()->value(function ($name) {
    return "<span class='label'>$name</span>";
});

$grid->email()->value(function ($email) {
    return "mailto:$email";
});
```

数据表格默认开启了批量删除操作，如果要关闭改功能可以使用：

```
$grid->disableBatchDeletion();
```

如果需要添加数据条件：
```
$grid->model()->where('id', '>', '20')->orderBy('updated_at', 'desc');
```

每页表格默认显示20条数据，修改为每页15条：
```
$grid->paginate(15);
```

使用以下方式来控制行操作：
```
//开启编辑和删除操作
$grid->actions('edit|delete');

//关闭所有操作
$grid->disableActions();
```



使用以下方式来控制行：
```
$grid->rows(function($row){

    //id小于10的行添加style
    if($row->id < 10) {
        $row->style('color:red');
    }
  
    //指定列只开启编辑操作
    if($row->id % 3) {
        $row->action('edit');
    }
    
    //指定列添加自定义操作按钮
    if($row->id % 2) {
        $row->actions()->add(function ($row) {
            return "<a class=\"btn btn-xs btn-danger\">btn</a>";
        });
    }
});
```

查询过滤器

```

//Add data grid filters.
$grid->filter(function($filter){

    // sql: ... WHERE `user.name` LIKE "%$name%";
    $filter->like('name', 'name');

    // sql: ... WHERE `user.email` = $email;
    $filter->is('emial', 'Email');

    // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
    $filter->between('created_at', 'Created Time')->datetime();
});

```

###创建数据表单

在`form()`方法中使用`Encore\Admin\Admin::form()`方法来创建数据表单容器，数据表单的内容都是在容器中定义的

```
use App\User;

protected function form()
{
    return Admin::form(User::class, function (Form $form) {

        $form->display('id', 'ID');
        $form->text('name');
        $form->email('email');
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');
    });
}

```

`$form`对象内置了大量创建表单元素的方法，下面是具体使用方法：

####text

文本输入框：

```
$form->text($column, [$label]);
```

####select
单选框，并设置选项:
```
$form->select($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

####multipleSelect
多选框，并设置选项:
```
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

####textarea
文本输入框:
```
$form->textarea($column[, $label]);
```

####radio
`radio`选择：
```
$form->radio($column[, $label])->values(['m' => 'Female', 'f'=> 'Male'])->default('m');
```

####checkbox
`values()`方法用来设置选择项:
```
$form->checkbox($column[, $label])->values([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

####email
填写email格式文本：
```
$form->email($column[, $label]);
```

####password
密码输入框：
```
$form->password($column[, $label]);
```

####url
填写合法的url文本：
```
$form->url($column[, $label]);
```

####ip
填写合法的ip地址：
```
$form->ip($column[, $label]);
```

####mobile
电话号码输入框，并设置格式：
```
$form->mobile($column[, $label])->format('999 9999 9999');
```

####color
颜色选择：
```
$form->color($column[, $label])->default('#ccc');
```

####time
时间输入框：
```
$form->time($column[, $label]);
```

####date
日期输入框：
```
$form->date($column[, $label]);
```

####datetime
日期时间输入框：
```
$form->datetime($column[, $label]);
```

####timeRange
时间范围选择，`$startTime`、`$endTime`为开始和结束时间字段:
```
$form->timeRange($startTime, $endTime, 'Time Range');
```

####dateRange
日期范围选择，`$startDate`、`$endDate`为开始和结束日期字段:
```
$form->dateRange($startDate, $endDate, 'Date Range');
```

####datetimeRange
时间日期范围选择，`$startDateTime`、`$endDateTime`为开始和结束时间日期:
```
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```

####currency
货币输入框，并设置单位符号：
```
$form->currency($column[, $label])->symbol('￥');
```

####number
输入数字：
```
$form->number($column[, $label]);
```

####rate
输入比例：
```
$form->rate($column[, $label]);
```

####image
图片上传，可以使用压缩、裁切、添加水印等各种方法，请参考[intervention](http://image.intervention.io/getting_started/introduction)：
```
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);
```

####file
文件上传，并设置上传文件类型:
```
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');
```

####map
地图控件，用来选择经纬度,`$latitude`, `$longitude`为经纬度字段，laravel的`locale`设置为`zh_CN`的时候使用腾讯地图，否则使用Google地图：
```
$form->map($latitude, $longitude, $label);
```

####slider
滑动选择控件,可以用来数字类型字段的选择，比如年龄：
```
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```

####editor
富文本编辑框:
```
$form->textarea($column[, $label]);
```

####json
json编辑框:
```
$form->json($column[, $label]);
```

####hidden
隐藏域:
```
$form->hidden($column);
```

####switch
开关，`on`和`off`对用开关的两个值:
```
$form->switch($column[, $label])->states(['on' => 1, 'off' => 0]);
```

####display
只显示字段：
```
$form->display($column[, $label]);
```

####divide
添加一条分割线:
```
$form->divide();
```

保存数据的时候添加回调，保存数据之前可以对提交数据做一些操作：

```
$form->saving(function(Form $form) {
    if($form->password && $form->model()->password != $form->password)
    {
        $form->password = bcrypt($form->password);
    }
});
```

###权限控制

`laravel-admin`已经内置了`RBAC`权限控制模块，展开左侧边栏的`Auth`，下面有用户、权限、角色三项的管理面板，权限控制的使用如下：
```
use Encore\Admin\Auth\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        // 检查权限，有user权限的角色可以访问
        Permission::check('user');
        
        // 'editor', 'developer'两个角色可以访问
        Permission::allow(['editor', 'developer']);
        
        // 'editor', 'developer'两个角色禁止访问
        Permission::deny(['editor', 'developer']);
    }
}
```

#License

[WTFPL](http://www.wtfpl.net/)
