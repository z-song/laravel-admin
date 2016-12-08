# 表单

`Encore\Admin\Widgets\Form`类用来快速构建表单：

```php

$form = new Form();

$form->action('example');

$form->email('email')->default('qwe@aweq.com');
$form->password('password');
$form->text('name', '输入框');
$form->url('url');
$form->color('color');
$form->map('lat', 'lng');
$form->date('date');
$form->json('val');
$form->dateRange('created_at', 'updated_at');

echo $form->render();
```
`Form::__construct($data = [])`生成一个form对象，如果传入了`$data`参数，`$data`数组中的元素将会按照`key`对应填入`form`对应name的表单中。

`Form::action($uri)`方法用来设置表单提交地址。

`Form::method($method)`方法用来设置form表单的提交方法,默认为`POST`方法。

`Form::disablePjax()` 不使用pjax方式提交表单。

# 表单元素
`Form`对象通过魔术方法`__call()`实现了近30种form元素的实现，可以通过简短的调用添加表单元素：

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