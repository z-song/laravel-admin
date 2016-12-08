# Form

`Encore\Admin\Widgets\Form` class is used to quickly build a form：

```php

$form = new Form();

$form->action('example');

$form->email('email')->default('qwe@aweq.com');
$form->password('password');
$form->text('name');
$form->url('url');
$form->color('color');
$form->map('lat', 'lng');
$form->date('date');
$form->json('val');
$form->dateRange('created_at', 'updated_at');

echo $form->render();
```

`Form::__construct($data = [])` generates a form object. If the `$data` parameter is passed, the elements in the` $data` array will be filled into the form.

`Form::action($uri)` method is used to set the form submission address.

`Form::method($method)` method is used to set the submit method of the form form, the default is `POST` method.

`Form::disablePjax()` disable pjax for form submit.

# The form elements
The `Form` object implements nearly 30 form elements via the magic method `__call()`, which adds the form element with a short call:

#### Text input

```php
$form->text($column, [$label]);

// Add a submission validation rule
$form->text($column, [$label])->rules('required|min:10');
```

#### Select
```php
$form->select($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### Multiple select
```php
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### Textarea
```php
$form->textarea($column[, $label]);
```

#### Radio
```php
$form->radio($column[, $label])->values(['m' => 'Female', 'f'=> 'Male'])->default('m');
```

#### Checkbox
The `values()` method is used to set options:
```php
$form->checkbox($column[, $label])->values([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### Email
```php
$form->email($column[, $label]);
```

#### Password
```php
$form->password($column[, $label]);
```

#### Url
```php
$form->url($column[, $label]);
```

#### Ip
```php
$form->ip($column[, $label]);
```

#### Mobile
```php
$form->mobile($column[, $label])->format('999 9999 9999');
```

#### Color
```php
$form->color($column[, $label])->default('#ccc');
```

#### Time
```php
$form->time($column[, $label]);

// 设置时间格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->time($column[, $label])->format('HH:mm:ss');
```

#### Date
```php
$form->date($column[, $label]);

// 设置日期格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->date($column[, $label])->format('YYYY-MM-DD');
```

#### Datetime
```php
$form->datetime($column[, $label]);

// Set the date format, more format reference http://momentjs.com/docs/#/displaying/format/
$form->datetime($column[, $label])->format('YYYY-MM-DD HH:mm:ss');
```

#### Time range
`$startTime`、`$endTime` is the start and end time fields:
```php
$form->timeRange($startTime, $endTime, 'Time Range');
```

#### Date range
`$startDate`、`$endDate` is the start and end date fields:
```php
$form->dateRange($startDate, $endDate, 'Date Range');
```

#### Datetime range
`$startDateTime`、`$endDateTime`is the start and end datetime fields:
```php
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```

#### Currency
```php
$form->currency($column[, $label]);

// Sets the unit symbol
$form->currency($column[, $label])->symbol('￥');

```

#### Number
```php
$form->number($column[, $label]);
```

#### Decimal
```php
$form->decimal($column[, $label]);
```

#### Rate
```php
$form->rate($column[, $label]);
```

#### Image upload
You can use compression, crop, add watermarks and other methods, please refer to [[Intervention](http://image.intervention.io/getting_started/introduction)],The image upload directory is configured in `upload` in the file `config/admin.php`. If the directory does not exist, it needs to be created and write-enabled. :
```php
$form->image($column[, $label]);

// Modify the image upload path and file name
$form->image($column[, $label])->move($dir, $name);

// Crop picture
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

// Add a watermark
$form->image($column[, $label])->insert($watermark, 'center');
```

#### File upload
文件上传目录在文件`config/admin.php`中的`upload.file`中配置，如果目录不存在，需要创建该目录并开放写权限。
The file upload directory is configured in `upload` in the file `config/admin.php`. If the directory does not exist, create the directory and open the write permissions.
```php
$form->file($column[, $label]);

// Modify the file upload path and file name
$form->file($column[, $label])->move($dir, $name);

// And set the upload file type
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');
```

#### Map
Map element is used to select the latitude and longitude, `$latitude`,`$longitude` for the latitude and longitude field, using Tencent map if the `locale` in `config/app.php` is set to` zh_CN`,otherwise use Google Maps:
```php
$form->map($latitude, $longitude, $label);

// Use Tencent map
$form->map($latitude, $longitude, $label)->useTencentMap();

// Use Google Maps
$form->map($latitude, $longitude, $label)->useGoogleMap();
```

#### Slider
Can be used to select the type of digital fields, such as age:
```php
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```

#### Editor
```php
$form->editor($column[, $label]);
```

#### Json Editor
```php
$form->json($column[, $label]);
```

#### Hidden
```php
$form->hidden($column);
```

#### Switch
`on` and `off` pairs of switches with the values `1` and` 0`:
```php
$form->switch($column[, $label])->states(['on' => 1, 'off' => 0]);
```

#### Display
Only the fields are displayed without any action:
```php
$form->display($column[, $label]);
```

#### Divide line
```php
$form->divide();
```