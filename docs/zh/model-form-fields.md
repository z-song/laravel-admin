# 表单组件

在`model-form`中内置了大量的form组件来帮助你快速的构建form表单

## 公共方法

### 设置验证规则

```php
$form->text('title')->rules('required|min:3');
```
更多规则请参考[Validation](https://laravel.com/docs/5.3/validation).

### 设置保存值
```php
$form->text('title')->value('text...');
```

### 设置默认值
```php
$form->text('title')->default('text...');
```

### 设置help信息
```php
$form->text('title')->help('help...');
```

### 设置属性
```php
$form->text('title')->attribute(['data-title' => 'title...']);

$form->text('title')->attribute('data-title', 'title...');
```

### 设置placeholder
```php
$form->text('title')->placeholder('请输入。。。');
```

### model-form-tab

如果表单元素太多,会导致form页面太长, 这种情况下可以使用tab来分隔form:

```php

$form->tab('Basic info', function ($form) {
    
    $form->text('username');
    $form->email('email');
    
})->tab('Profile', function ($form) {
                       
   $form->image('avatar');
   $form->text('address');
   $form->mobile('phone');
   
})->tab('Jobs', function ($form) {
                         
     $form->hasMany('jobs', function () {
         $form->text('company');
         $form->date('start_date');
         $form->date('end_date');
     });

  })

```

## 基本使用

### 文本输入框

```php
$form->text($column, [$label]);

// 添加提交验证规则
$form->text($column, [$label])->rules('required|min:10');
```

### select选择框
```php
$form->select($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

如果选项过多，可通过ajax方式动态分页载入选项：

```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');
```

API `/admin/api/users`接口的代码：

```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}

```
接口返回的数据结构为
```
{
    "total": 4,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 3,
    "data": [
        {
            "id": 9,
            "text": "xxx"
        },
        {
            "id": 21,
            "text": "xxx"
        },
        {
            "id": 42,
            "text": "xxx"
        },
        {
            "id": 48,
            "text": "xxx"
        }
    ]
}
```

### 多选框

```php
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

多选框可以处理两种情况，第一种是`ManyToMany`的关系。

```

class Post extends Models
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

$form->multipleSelect('tags')->options(Tag::all()->pluck('name', 'id'));

```

第二种是选项值以逗号`,`隔开，存储在字符串字段里。


如果选项过多，可通过ajax方式动态分页载入选项：

```php
$form->select('friends')->options(function ($ids) {

    return User::find($ids)->pluck('name', 'id');
    
})->ajax('/admin/api/users');
```

API `/admin/api/users`接口的代码：

```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}

```
接口返回的数据结构为
```
{
    "total": 4,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "next_page_url": null,
    "prev_page_url": null,
    "from": 1,
    "to": 3,
    "data": [
        {
            "id": 9,
            "text": "xxx"
        },
        {
            "id": 21,
            "text": "xxx"
        },
        {
            "id": 42,
            "text": "xxx"
        },
        {
            "id": 48,
            "text": "xxx"
        }
    ]
}
```

### textarea输入框
```php
$form->textarea($column[, $label])->rows(10);
```

### radio选择
```php
$form->radio($column[, $label])->options(['m' => 'Female', 'f'=> 'Male'])->default('m');
```

### checkbox选择

`checkbox`能处理两种数据存储情况，参考[多选框](#多选框)

`options()`方法用来设置选择项:
```php
$form->checkbox($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

### email个数输入框
```php
$form->email($column[, $label]);
```

### 密码输入框
```php
$form->password($column[, $label]);
```

### url输入框
```php
$form->url($column[, $label]);
```

### ip输入框
```php
$form->ip($column[, $label]);
```

### 电话号码输入框
```php
$form->mobile($column[, $label])->options(['mask' => '999 9999 9999']);
```

### 颜色选择框
```php
$form->color($column[, $label])->default('#ccc');
```

### 时间输入框
```php
$form->time($column[, $label]);

// 设置时间格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->time($column[, $label])->format('HH:mm:ss');
```

### 日期输入框
```php
$form->date($column[, $label]);

// 设置日期格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->date($column[, $label])->format('YYYY-MM-DD');
```

### 日期时间输入框
```php
$form->datetime($column[, $label]);

// 设置日期格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->datetime($column[, $label])->format('YYYY-MM-DD HH:mm:ss');
```

### 时间范围选择框
`$startTime`、`$endTime`为开始和结束时间字段:
```php
$form->timeRange($startTime, $endTime, 'Time Range');
```

### 日期范围选框
`$startDate`、`$endDate`为开始和结束日期字段:
```php
$form->dateRange($startDate, $endDate, 'Date Range');
```

### 时间日期范围选择框
`$startDateTime`、`$endDateTime`为开始和结束时间日期:
```php
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```

### 货币输入框
```php
$form->currency($column[, $label]);

// 设置单位符号
$form->currency($column[, $label])->symbol('￥');

```

### 数字输入框
```php
$form->number($column[, $label]);
```

### 比例输入框
```php
$form->rate($column[, $label]);
```

### 图片上传

使用图片上传功能之前需要先完成上传配置，请参考:[图片/文件上传](form-upload.md).

图片上传目录在文件`config/admin.php`中的`upload.image`中配置，如果目录不存在，需要创建该目录并开放写权限。

可以使用压缩、裁切、添加水印等各种方法,需要先安装[intervention/image](http://image.intervention.io/getting_started/installation).

更多使用方法请参考[[Intervention](http://image.intervention.io/getting_started/introduction)]：
```php
$form->image($column[, $label]);

// 修改图片上传路径和文件名
$form->image($column[, $label])->move($dir, $name);

// 剪裁图片
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

// 加水印
$form->image($column[, $label])->insert($watermark, 'center');

// 多图上传，图片的路径会以JSON的格式存储在数据库中
$form->image($column[, $label])->multiple();
```

### 文件上传

使用图片上传功能之前需要先完成上传配置，请参考:[图片/文件上传](form-upload.md).

文件上传目录在文件`config/admin.php`中的`upload.file`中配置，如果目录不存在，需要创建该目录并开放写权限。
```php
$form->file($column[, $label]);

// 修改文件上传路径和文件名
$form->file($column[, $label])->move($dir, $name);

// 并设置上传文件类型
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');

// 多文件上传，文件的路径会以JSON的格式存储在数据库中
$form->file($column[, $label])->multiple();
```

### 地图控件

地图组件引用了网络资源，默认关闭,如果要开启这个组件参考[form组件管理](field-management.md)

地图控件，用来选择经纬度,`$latitude`, `$longitude`为经纬度字段，`Laravel`的`locale`设置为`zh_CN`的时候使用腾讯地图，否则使用Google地图：
```php
$form->map($latitude, $longitude, $label);
```

### 滑动选择控件
可以用来数字类型字段的选择，比如年龄：
```php
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```

### 富文本编辑框

编辑器组件引用了网络资源，默认关闭,如果要开启这个组件参考[form组件管理](field-management.md).

```php
$form->editor($column[, $label]);
```

### 隐藏域
```php
$form->hidden($column);
```

### 开关选择
`on`和`off`对用开关的两个值`1`和`0`:
```php
$states = [
    'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
    'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
];

$form->switch($column[, $label])->states($states);
```

### 显示字段
只显示字段，不做任何操作：
```php
$form->display($column[, $label]);
```

### 分割线
```php
$form->divide();
```

### Html
插入html内容，参数可以是实现了`Htmlable`、`Renderable`或者实现了`__toString()`方法的类
```php
$form->html('你的html内容', $label = '');
```

### tags
插入逗号(,)隔开的字符串`tags`
```php
$form->tags('keywords');
```

### icon
选择`font-awesome`图标
```php
$form->icon('icon');
```

### hasMany

一对多内嵌表格，用于处理一对多的关系，下面是个简单的例子：

有两张表是一对多关系：

```sql
CREATE TABLE `demo_painters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `demo_paintings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `painter_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY painter_id (`painter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

表的模型为：
```php
<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Model;

class Painter extends Model
{
    public function paintings()
    {
        return $this->hasMany(Painting::class, 'painter_id');
    }
}

<?php

namespace App\Models\Demo;

use Illuminate\Database\Eloquent\Model;

class Painting extends Model
{
    protected $fillable = ['title', 'body', 'completed_at'];

    public function painter()
    {
        return $this->belongsTo(Painter::class, 'painter_id');
    }
}
```

构建表单代码如下：
```php
$form->display('id', 'ID');

$form->text('username')->rules('required');
$form->textarea('bio')->rules('required');

$form->hasMany('paintings', function (Form\NestedForm $form) {
    $form->text('title');
    $form->image('body');
    $form->datetime('completed_at');
});

$form->display('created_at', 'Created At');
$form->display('updated_at', 'Updated At');
```

### embeds

用于处理`mysql`的`JSON`类型字段数据或者`mongodb`的`object`类型数据，也可以将多个field的数据值以`JSON`字符串的形式存储在`mysql`的字符创类型字段中

比如`orders`表中的`JSON`或字符串类型的`extra`字段，用来存储多个field的数据，先定义model:
```php
class Order extends Model
{
    protected $casts = [
        'extra' => 'json',
    ];
}
```
然后在form中使用：
```php
$form->embeds('extra', function ($form) {

    $form->text('extra1')->rules('required');
    $form->email('extra2')->rules('required');
    $form->mobile('extra3');
    $form->datetime('extra4');

    $form->dateRange('extra5', 'extra6', '范围')->rules('required');

});

// 自定义标题
$form->embeds('extra', '附加信息', function ($form) {
    ...
});
```

回调函数里面构建表单元素的方法调用和外面是一样的。