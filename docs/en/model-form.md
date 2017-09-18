# Model-Form

The `Encore\Admin\Form` class is used to generate a data model-based form. For example, there is a` movies` table in the database

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

The corresponding data model is `App\Models\Movie`, and the following code can generate the` movies` data form:

```php

use App\Models\Movie;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;

$grid = Admin::form(Movie::class, function(Form $grid){

    // Displays the record id
    $form->display('id', 'ID');

    // Add an input box of type text
    $form->text('title', 'Movie title');
    
    $directors = [
        'John'  => 1,
        'Smith' => 2,
        'Kate'  => 3,
    ];
    
    $form->select('director', 'Director')->options($directors);
    
    // Add textarea for the describe field
    $form->textarea('describe', 'Describe');
    
    // Number input
    $form->number('rate', 'Rate');
    
    // Add a switch field
    $form->switch('released', 'Released?');
    
    // Add a date and time selection box
    $form->dateTime('release_at', 'release time');
    
    // Display two time column 
    $form->display('created_at', 'Created time');
    $form->display('updated_at', 'Updated time');
});

// Displays the form content
echo $form;

```

# Basic Usage

#### Text input

```php
$form->text($column, [$label]);

// Add a submission validation rule
$form->text($column, [$label])->rules('required|min:10');
```

#### select
```php
$form->select($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

Or load option by ajax
```php
$form->select($column[, $label])->options('/admin/demo/options');
```

The json format returned from url `/admin/demo/options`:
```
[
    {
        "id": 1,
        "text": "hello"
    },
    {
        "id": 2,
        "text": "world"
    },
]
```

#### Multiple select
```php
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

Or load option by ajax
```php
$form->multipleSelect($column[, $label])->options('/admin/demo/options');
```

The json format returned from url `/admin/demo/options`:
```
[
    {
        "id": 1,
        "text": "hello"
    },
    {
        "id": 2,
        "text": "world"
    },
]
```

#### textarea:
```php
$form->textarea($column[, $label]);
```

#### radio
```php
$form->radio($column[, $label])->values(['m' => 'Female', 'f'=> 'Male'])->default('m');
```

#### checkbox
The `values ()` method is used to set options:
```php
$form->checkbox($column[, $label])->values([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

#### email input
```php
$form->email($column[, $label]);
```

#### password input
```php
$form->password($column[, $label]);
```

#### url input
```php
$form->url($column[, $label]);
```

#### ip input
```php
$form->ip($column[, $label]);
```

#### phone number input
```php
$form->mobile($column[, $label])->format('999 9999 9999');
```

#### color select
```php
$form->color($column[, $label])->default('#ccc');
```

#### time select
```php
$form->time($column[, $label]);

// Set the time format, more formats reference http://momentjs.com/docs/#/displaying/format/    
$form->time($column[, $label])->format('HH:mm:ss');
```

#### date input
```php
$form->date($column[, $label]);

// 设置日期格式，更多格式参考http://momentjs.com/docs/#/displaying/format/
$form->date($column[, $label])->format('YYYY-MM-DD');
```

#### datetime input
```php
$form->datetime($column[, $label]);

// Set the date format, more format reference http://momentjs.com/docs/#/displaying/format/
$form->datetime($column[, $label])->format('YYYY-MM-DD HH:mm:ss');
```

#### time range select
`$startTime`、`$endTime`is the start and end time fields:
```php
$form->timeRange($startTime, $endTime, 'Time Range');
```

#### date range select
`$startDate`、`$endDate`is the start and end date fields:
```php
$form->dateRange($startDate, $endDate, 'Date Range');
```

#### datetime range select
`$startDateTime`、`$endDateTime` is the start and end datetime fields:
```php
$form->datetimeRange($startDateTime, $endDateTime, 'DateTime Range');
```

#### currency input
```php
$form->currency($column[, $label]);

// set the unit symbol
$form->currency($column[, $label])->symbol('￥');

```

#### number input
```php
$form->number($column[, $label]);
```

#### rate input
```php
$form->rate($column[, $label]);
```

#### image upload
You can use compression, crop, add watermarks and other methods, please refer to [[Intervention] (http://image.intervention.io/getting_started/introduction)], picture upload directory in the file `config / admin.php` `Upload.image` configuration, if the directory does not exist, you need to create the directory and open write permissions:
```php
$form->image($column[, $label]);

// Modify the image upload path and file name
$form->image($column[, $label])->move($dir, $name);

// Crop picture
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

// Add a watermark
$form->image($column[, $label])->insert($watermark, 'center');
```

#### file upload
The file upload directory is configured in `upload.file` in the file `config/admin.php`. If the directory does not exist, it needs to be created and write-enabled.
```php
$form->file($column[, $label]);

// Modify the file upload path and file name
$form->file($column[, $label])->move($dir, $name);

// And set the upload file type
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');
```

#### map
Used to select the latitude and longitude, `$ latitude`,` $ longitude` for the latitude and longitude field, using Tencent map when `locale` set of laravel is` zh_CN`, otherwise use Google Maps:
```php
$form->map($latitude, $longitude, $label);

// Use Tencent map
$form->map($latitude, $longitude, $label)->useTencentMap();

// Use google map
$form->map($latitude, $longitude, $label)->useGoogleMap();
```

#### slide
Can be used to select the type of digital fields, such as age:
```php
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```

#### rich text editor
```php
$form->editor($column[, $label]);
```

#### json editor
```php
$form->json($column[, $label]);
```

#### hidden field
```php
$form->hidden($column);
```

#### switch
`On` and` off` pairs of switches with the values `1` and` 0`:
```php
$form->switch($column[, $label])->states(['on' => 1, 'off' => 0]);
```

#### display field
Only display the fields and without any action:
```php
$form->display($column[, $label]);
```

#### divide
```php
$form->divide();
```

#### saving callback
Add callback function when saving data, can use it do some operations when saving data.
```php
$form->saving(function(Form $form) {
    if($form->password && $form->model()->password != $form->password)
    {
        $form->password = bcrypt($form->password);
    }
});
```