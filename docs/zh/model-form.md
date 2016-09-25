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

对应的数据模型为`App\Models\Movie`，下面的代码可以生成`users`的数据表单：

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
});

// 显示表单内容
echo $form;

```

# Basic Usage

#### 文本输入框

```php
$form->text($column, [$label]);

// 添加提交验证规则
$form->text($column, [$label])->rules('required|min:10');
```

#### select选择框
```php
$form->select($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### 多选框
```php
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### textarea输入框:
```php
$form->textarea($column[, $label]);
```

#### radio选择
```php
$form->radio($column[, $label])->values(['m' => 'Female', 'f'=> 'Male'])->default('m');
```

#### checkbox选择
`values()`方法用来设置选择项:
```php
$form->checkbox($column[, $label])->values([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### email个数输入框
```php
$form->email($column[, $label]);
```

#### 密码输入框
```php
$form->password($column[, $label]);
```

#### url输入框
```php
$form->url($column[, $label]);
```

#### ip输入框
```php
$form->ip($column[, $label]);
```

#### 电话号码输入框
```php
$form->mobile($column[, $label])->format('999 9999 9999');
```

#### 颜色选择框
```php
$form->color($column[, $label])->default('#ccc');
```

#### 时间输入框
```php
$form->time($column[, $label]);

// 设置时间格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->time($column[, $label])->format('HH:mm:ss');
```

#### 日期输入框
```php
$form->date($column[, $label]);

// 设置日期格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->date($column[, $label])->format('YYYY-MM-DD');
```

#### 日期时间输入框
```php
$form->datetime($column[, $label]);

// 设置日期格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->datetime($column[, $label])->format('YYYY-MM-DD HH:mm:ss');
```

#### 时间范围选择框
`$startTime`、`$endTime`为开始和结束时间字段:
```php
$form->timeRange($startTime, $endTime, 'Time Range');
```

#### 日期范围选框
`$startDate`、`$endDate`为开始和结束日期字段:
```php
$form->dateRange($startDate, $endDate, 'Date Range');
```

#### 时间日期范围选择框
`$startDateTime`、`$endDateTime`为开始和结束时间日期:
```php
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```

#### 货币输入框
```php
$form->currency($column[, $label]);

// 设置单位符号
$form->currency($column[, $label])->symbol('￥');

```

#### 数字输入框
```php
$form->number($column[, $label]);
```

#### 比例输入框
```php
$form->rate($column[, $label]);
```

#### 图片上传
可以使用压缩、裁切、添加水印等各种方法，请参考[[Intervention](http://image.intervention.io/getting_started/introduction)]，图片上传目录在文件`config/admin.php`中的`upload.image`中配置，如果目录不存在，需要创建该目录并开放写权限。：
```php
$form->image($column[, $label]);

// 修改图片上传路径和文件名
$form->image($column[, $label])->move($dir, $name);

// 剪裁图片
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

// 加水印
$form->image($column[, $label])->insert($watermark, 'center');
```

#### 文件上传
文件上传目录在文件`config/admin.php`中的`upload.file`中配置，如果目录不存在，需要创建该目录并开放写权限。
```php
$form->file($column[, $label]);

// 修改文件上传路径和文件名
$form->file($column[, $label])->move($dir, $name);

// 并设置上传文件类型
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');
```

#### 地图控件
地图控件，用来选择经纬度,`$latitude`, `$longitude`为经纬度字段，laravel的`locale`设置为`zh_CN`的时候使用腾讯地图，否则使用Google地图：
```php
$form->map($latitude, $longitude, $label);

// 使用腾讯地图
$form->map($latitude, $longitude, $label)->useTencentMap();

// 使用Google地图
$form->map($latitude, $longitude, $label)->useGoogleMap();
```

#### 滑动选择控件
可以用来数字类型字段的选择，比如年龄：
```php
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```

#### 富文本编辑框
```php
$form->editor($column[, $label]);
```

#### json编辑框
```php
$form->json($column[, $label]);
```

#### 隐藏域
```php
$form->hidden($column);
```

#### 开关选择
`on`和`off`对用开关的两个值`1`和`0`:
```php
$form->switch($column[, $label])->states(['on' => 1, 'off' => 0]);
```

#### 显示字段
只显示字段，不做任何操作：
```php
$form->display($column[, $label]);
```

#### 分割线
```php
$form->divide();
```

#### 保存数据回调
保存数据的时候添加回调，保存数据之前可以对提交数据做一些操作：
```php
$form->saving(function(Form $form) {
    if($form->password && $form->model()->password != $form->password)
    {
        $form->password = bcrypt($form->password);
    }
});
```