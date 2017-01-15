# file/image upload

[model-form](/docs/zh/model-form.md) can build file and image upload field with following codes

```php
$form->file('file_column');
$form->image('image_column');
```

### Change store path and name

```php

// change upload path
$form->image('picture')->move('public/upload/image1/');

// use a unique name (md5(uniqid()).extension)
$form->image('picture')->uniqueName();

// specify filename
$form->image('picture')->name(function ($file) {
    return 'test.'.$file->guessExtension();
});

```

[model-form](/docs/zh/model-form.md) both support for local and cloud storage upload

### upload to local

first add storage configuration, add a disk in `config/filesystems.php`:

```php

'disks' => [
    ... ,

    'admin' => [
        'driver' => 'local',
        'root' => public_path('upload'),
        'visibility' => 'public',
    ],
],

```

set upload path to `public/upload`(public_path('upload'))。

And then in `config/admin.php` select the `disk` set up above：

```php

'upload'  => [

    'disk' => 'admin',

    'directory'  => [
        'image'  => 'image',
        'file'   => 'file',
    ],

    'host' => 'http://localhost:8000/upload/',
],

```

Set `disk` to the` admin` that you added above,`directory.image` and `directory.file` is the upload path for `$form->image($column)` and `$form->file($column)`.

`host` is url prefix for your uploaded files.


### upload to clould

If you need to upload to the cloud storage, need to install a driver which supports `flysystem` adapter, take `qiniu` cloud storage as example.

first install [zgldh/qiniu-laravel-storage](https://github.com/zgldh/qiniu-laravel-storage).

Also configure the disk, in the `config/filesystems.php` add an item:

```php
'disks' => [
    ... ,
    'qiniu' => [
        'driver'  => 'qiniu',
        'domains' => [
            'default'   => 'xxxxx.com1.z0.glb.clouddn.com', 
            'https'     => 'dn-yourdomain.qbox.me',       
            'custom'    => 'static.abc.com',              
         ],
        'access_key'=> '',  //AccessKey
        'secret_key'=> '',  //SecretKey
        'bucket'    => '',  //Bucket名字
        'notify_url'=> '',  //
    ],
],

```

Then modify the upload configuration of `laravel-admin` and open `config/admin.php` to find:

```php

'upload'  => [

    'disk' => 'qiniu',

    'directory'  => [
        'image'  => 'image',
        'file'   => 'file',
    ],

    'host' => 'http://of8kfibjo.bkt.clouddn.com/',
],

```

Select the above configuration` qiniu` for `disk`, `host` configured to `qiniu` cloud storage test domain name.