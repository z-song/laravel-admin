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

#### 禁用创建按钮
```php
$grid->disableCreation();
```

#### 禁用导出数据按钮
```php
$grid->disableExport();
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
        $row->actions('edit');
    }
   
    //指定列添加自定义操作按钮
    if($row->id % 2) {
        $row->actions()->add(function ($row) {
            return "<a class=\"btn btn-xs btn-danger\">btn</a>";
        });
    }
});
```
#### 添加自定义操作按钮
```php
$grid->actions(function(Actions $action){

        //在操作按钮组前添加
        $action->prepend("<a  href='".route('exampleImageSave',['id'=>$action->getkey()])."' ><i class='fa fa-image'></i></a>");
        
        //在操作按钮组后添加
        $action->append("<a  href='".route('exampleImageSave',['id'=>$action->getkey()])."' ><i class='fa fa-image'></i></a>");
});
```

#### 添加查询过滤器
```php
$grid->filter(function($filter){

    // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
    $filter->useModal();

    // sql: ... WHERE `user.name` LIKE "%$name%";
    $filter->like('name', 'name');

    // sql: ... WHERE `user.email` = $email;
    $filter->equal('emial', 'Email');

    // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
    $filter->between('created_at', 'Created Time')->datetime();
    
    // sql: ... WHERE `article.author_id` = $id;
    $filter->equal('author_id', 'Author')->select(User::all()->pluck('name', 'id'));

    // sql: ... WHERE `title` LIKE "%$input" OR `content` LIKE "%$input";
    $filter->where(function ($query) {

        $query->where('title', 'like', "%{$this->input}%")
            ->orWhere('content', 'like', "%{$this->input}%");

    }, 'Text');
    
    // sql: ... WHERE `rate` >= 6 AND `created_at` = {$input};
    $filter->where(function ($query) {

        $query->whereRaw("`rate` >= 6 AND `created_at` = {$this->input}");

    }, 'Text');
});
```

## 关联模型


### 一对一
`users`表和`profiles`表通过`profiles.user_id`字段生成一对一关联

```sql

CREATE TABLE `users` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `profiles` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`age` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

对应的数据模分别为:

```php

class User extends Model
{
    public function profile()
    {
        $this->hasOne(Profile::class);
    }
}

class Profile extends Model
{
    $this->hasOne(User::class);
}

```

通过下面的代码可以关联在一个grid里面:

```php
Admin::grid(User::class, function (Grid $grid) {

    $grid->id('ID')->sortable();

    $grid->name();
    $grid->email();
    
    $grid->column('profile.age');
    $grid->column('profile.gender');

    //or
    $grid->profile()->age();
    $grid->profile()->gender();

    $grid->created_at();
    $grid->updated_at();
});

```

### 一对多

`posts`表和`comments`表通过`comments.post_id`字段生成一对多关联

```sql

CREATE TABLE `posts` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `comments` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`post_id` int(10) unsigned NOT NULL,
`content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

对应的数据模分别为:

```php

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

class Comment extends Model
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

```

通过下面的代码可以让两个模型在grid里面互相关联:

```php

return Admin::grid(Post::class, function (Grid $grid) {
    $grid->id('id')->sortable();
    $grid->title();
    $grid->content();

    $grid->comments('评论数')->value(function ($comments) {
        $count = count($comments);
        return "<span class='label label-warning'>{$count}</span>";
    });

    $grid->created_at();
    $grid->updated_at();
});


return Admin::grid(Comment::class, function (Grid $grid) {
    $grid->id('id');
    $grid->post()->title();
    $grid->content();

    $grid->created_at()->sortable();
    $grid->updated_at();
});

```

### 多对多

`users`和`roles`表通过中间表`role_users`产生多对多关系

```sql

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```

对应的数据模分别为:

```php

class User extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

```

通过下面的代码可以让两个模型在grid里面互相关联:


```php
return Admin::grid(User::class, function (Grid $grid) {
    $grid->id('ID')->sortable();
    $grid->username();
    $grid->name();

    $grid->roles()->value(function ($roles) {

        $roles = array_map(function ($role) {
            return "<span class='label label-success'>{$role['name']}</span>";
        }, $roles);

        return join('&nbsp;', $roles);
    });

    $grid->created_at();
    $grid->updated_at();
});

```
