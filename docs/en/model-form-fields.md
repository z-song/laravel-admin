# Builtin form fields

There are a lots of form components built into the `model-form` to help you quickly build forms.

## Public methods

### Set validation rules

```php
$form->text('title')->rules('required|min:3');
```
Please refer to the more rules:[Validation](https://laravel.com/docs/5.3/validation).

### Set the value to save
```php
$form->text('title')->value('text...');
```

### Set default value
```php
$form->text('title')->default('text...');
```

### Set help message
```php
$form->text('title')->help('help...');
```

### Set attributes of field element
```php
$form->text('title')->attribute(['data-title' => 'title...']);

$form->text('title')->attribute('data-title', 'title...');
```

### Set placeholder
```php
$form->text('title')->placeholder('Please input...');
```

### model-form-tab

If the form contains too many fields, will lead to form page is too long, in which case you can use the tab to separate the form:

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

If have too many options, you can load option by ajax:

```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');
```

The controller method for api `/admin/api/users` is:

```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}

```

The json returned from api `/admin/demo/options`:
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

#### Multiple select
```php
$form->multipleSelect($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
```

You can store value of multiple select in two ways, one is `many-to-many` relation.

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

The other is store values as string format separated by `,`.

If have too many options, you can load option by ajax

```php
$form->select('user_id')->options(function ($id) {
    $user = User::find($id);

    if ($user) {
        return [$user->id => $user->name];
    }
})->ajax('/admin/api/users');
```

The controller method for api `/admin/api/users` is:

```php
public function users(Request $request)
{
    $q = $request->get('q');

    return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
}

```

The json returned from api `/admin/demo/options`:
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

#### textarea
```php
$form->textarea($column[, $label])->rows(10);
```

#### radio
```php
$form->radio($column[, $label])->options(['m' => 'Female', 'f'=> 'Male'])->default('m');
```

#### checkbox

`checkbox` can store values in two ways, see[multiple select](#Multiple select)

The `options()` method is used to set options:
```php
$form->checkbox($column[, $label])->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);
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

#### time input
```php
$form->time($column[, $label]);

// Set the time format, more formats reference http://momentjs.com/docs/#/displaying/format/    
$form->time($column[, $label])->format('HH:mm:ss');
```

#### date input
```php
$form->date($column[, $label]);

// Date format setting，more format please see http://momentjs.com/docs/#/displaying/format/
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

Before use upload field, you must complete upload configuration, see [image/file upload](/docs/en/form-upload.md).

You can use compression, crop, add watermarks and other methods, please refer to [[Intervention] (http://image.intervention.io/getting_started/introduction)], picture upload directory in the file `config / admin.php` `Upload.image` configuration, if the directory does not exist, you need to create the directory and open write permissions:
```php
$form->image($column[, $label]);

// Modify the image upload path and file name
$form->image($column[, $label])->move($dir, $name);

// Crop picture
$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

// Add a watermark
$form->image($column[, $label])->insert($watermark, 'center');

// multiple image upload, the path of images will store in database with JSON format
$form->image($column[, $label])->multiple();
```

#### file upload

Before use upload field, you must complete upload configuration, see [image/file upload](/docs/en/form-upload.md).

The file upload directory is configured in `upload.file` in the file `config/admin.php`. If the directory does not exist, it needs to be created and write-enabled.
```php
$form->file($column[, $label]);

// Modify the file upload path and file name
$form->file($column[, $label])->move($dir, $name);

// And set the upload file type
$form->file($column[, $label])->rules('mimes:doc,docx,xlsx');

// multiple file upload, the path of files will store in database with JSON format
$form->file($column[, $label])->multiple();
```

#### map

The map field refers to the network resource, and if there is a problem with the network refer to [form Component Management](/docs/en/field-management.md) to remove the component.

Used to select the latitude and longitude, `$ latitude`,` $ longitude` for the latitude and longitude field, using Tencent map when `locale` set of laravel is` zh_CN`, otherwise use Google Maps:
```php
$form->map($latitude, $longitude, $label);

// Use Tencent map
$form->map($latitude, $longitude, $label)->useTencentMap();

// Use google map
$form->map($latitude, $longitude, $label)->useGoogleMap();
```

#### slider
Can be used to select the type of digital fields, such as age:
```php
$form->slider($column[, $label])->options(['max' => 100, 'min' => 1, 'step' => 1, 'postfix' => 'years old']);
```

#### rich text editor

The editor field refers to the network resource, and if there is a problem with the network refer to [form Component Management](/docs/en/field-management.md) to remove the component.

```php
$form->editor($column[, $label]);
```

#### hidden field
```php
$form->hidden($column);
```

#### switch
`On` and` off` pairs of switches with the values `1` and` 0`:
```php
$states = [
    'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
    'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
];

$form->switch($column[, $label])->states($states);
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

#### Html
insert html，the argument passed in could be objects which impletements `Htmlable`、`Renderable`, or has method `__toString()`
```php
$form->html('html contents');
```

### tags
Insert the comma (,) separated string `tags`
```php
$form->tags('keywords');
```

### icon
Select the `font-awesome` icon.
```php
$form->icon('icon');
```

### hasMany

One-to-many built-in tables for dealing with one-to-many relationships. Here is a simple example:

There are two tables are one-to-many relationship:

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

The model of tables are:
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

Build the form code as follows:
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
