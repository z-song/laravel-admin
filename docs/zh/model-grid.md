# 基于数据模型的表格

`Encore\Admin\Grid`类用于生成基于数据模型的表格，先来个例子，数据库中有`movies`表

```sql
CREATE TABLE `movies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` int(10) unsigned NOT NULL,
  `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` tinyint unsigned NOT NULL,
  `released` enum(0, 1),
  `release_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

```

对应的数据模型为`App\Models\Movie`，下面的代码可以生成`users`的数据表格：

```php

use App\Models\Movie;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;

$grid = Admin::grid(Movie::class, function(Grid $grid){

    // 第一列显示id字段，并将这一列设置为可排序列
    $grid->id('ID')->sortable();

    // 第二列显示title字段，由于title字段名和Grid对象的title方法冲突，所以用Grid的column()方法代替
    $grid->column('title');
    
    // 第三列显示director字段，通过value($callback)方法设置这一列的显示内容为users表中对应的用户名
    $grid->director()->value(function($userId) {
        return User::find($userId)->name;
    });
    
    // 第四列显示为describe字段
    $grid->describe();
    
    // 第五列显示为rate字段
    $grid->rate();

    // 第六列显示released字段，通过value($callback)方法来格式化显示输出
    $grid->released('上映?')->value(function ($released) {
        return $released ? '是' : '否';
    });

    // 下面为三个时间字段的列显示
    $grid->release_at();
    $grid->created_at();
    $grid->updated_at();

    // filter($callback)方法用来设置表格的简单搜索框
    $grid->filter(function ($filter) {
    
        // 设置created_at字段的范围查询
        $filter->between('created_at', 'Created Time')->datetime();
    });
});

// 显示表格内容
echo $grid;

```

## Basic Usage

#### 设置表格title
```php
$grid->title('电影列表');
```

#### 添加列
```php

// 直接通过字段名`username`添加列
$grid->username('用户名');

// 效果和上面一样
$grid->column('username', '用户名');

// 添加多列
$grid->columns('email', 'username' ...);
```

#### 修改来源数据
```php
$grid->model()->where('id', '>', 100);

$grid->model()->orderBy('id', 'desc');

$grid->model()->take(100);

```

#### 设置每页显示行数

```php
// 默认为每页20条
$grid->paginate(15);
```

#### 修改显示输出

```php
$grid->text()->value(function($text) {
    return str_limit($text, 30, '...');
});

$grid->name()->value(function ($name) {
    return "<span class='label'>$name</span>";
});

$grid->email()->value(function ($email) {
    return "mailto:$email";
});

```

#### 禁用批量删除按钮
```php
$grid->disableBatchDeletion();
```
#### 修改行操作按钮
```php
//开启编辑和删除操作
$grid->actions('edit|delete');

//关闭所有操作
$grid->disableActions();
```

#### 控制列
```php
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

#### 添加查询过滤器
```php
$grid->filter(function($filter){

    // sql: ... WHERE `user.name` LIKE "%$name%";
    $filter->like('name', 'name');

    // sql: ... WHERE `user.email` = $email;
    $filter->is('emial', 'Email');

    // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
    $filter->between('created_at', 'Created Time')->datetime();
});
```