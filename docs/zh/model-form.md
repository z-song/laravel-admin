# 基于数据模型的表单

`Encore\Admin\Form`类用于生成基于数据模型的表单，先来个例子，数据库中有`movies`表

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

对应的数据模型为`App\Models\Movie`，下面的代码可以生成`movies`的数据表单：

```php

use App\Models\Movie;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;

$grid = Admin::form(Movie::class, function(Form $grid){

    // 显示记录id
    $form->display('id', 'ID');

    // 添加text类型的input框
    $form->text('title', '电影标题');
    
    $directors = [
        'John'  => 1,
        'Smith' => 2,
        'Kate'  => 3,
    ];
    
    $form->select('director', '导演')->options($directors);
    
    // 添加describe的textarea输入框
    $form->textarea('describe', '简介');
    
    // 数字输入框
    $form->number('rate', '打分');
    
    // 添加开关操作
    $form->switch('released', '发布？');
    
    // 添加日期时间选择框
    $form->dateTime('release_at', '发布时间');
    
    // 两个时间显示
    $form->display('created_at', '创建时间');
    $form->display('updated_at', '修改时间');
    
    // 去掉form删除功能（移除删除按钮）
    $form->disableDeletion();
});

// 显示表单内容
echo $form;

```

## 其它方法

### 忽略掉不需要保存的字段
```php
$form->ignore(['column1', 'column2', 'column3']);
```
