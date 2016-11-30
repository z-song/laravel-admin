# 文件/图片上传

[model-form](/docs/zh/model-form.md)通过以下的调用来生成form元素。

```php
$form->file('file_column');
$form->image('image_column');
```

### 修改存储路径或文件名

```php

// 修改上传目录
$form->image('picture')->move('public/upload/image1/');

// 使用随机生成文件名 (md5(uniqid()).extension)
$form->image('picture')->uniqueName();

// 自定义文件名
$form->image('picture')->name(function ($file) {
    return 'test.'.$file->guessExtension();
});

```

[model-form](/docs/zh/model-form.md)支持本地和云存储的文件上传

### 本地上传

先添加存储配置，`config/filesystems.php` 添加一项`disk`:

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

设置上传的路径为`public/upload`(public_path('upload'))。

然后选择上传的`disk`，打开`config/admin.php`找到：

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

将`disk`设置为上面添加的`admin`，`directory.image`和`directory.file`分别为用`$form->image($column)`和`$form->file($column)`上传的图片和文件的上传目录

`host`为图片和文件的网络访问url前缀。


### 云盘上传

如果需要上传到云存储，需要安装兼容`laravel storage`操作方式的driver，拿七牛云存储举例

首先安装 [zgldh/qiniu-laravel-storage](https://github.com/zgldh/qiniu-laravel-storage)

同样配置好disk，在`config/filesystems.php` 添加一项:

```php
'disks' => [
    ... ,
    'qiniu' => [
        'driver'  => 'qiniu',
        'domains' => [
            'default'   => 'xxxxx.com1.z0.glb.clouddn.com', //你的七牛域名
            'https'     => 'dn-yourdomain.qbox.me',         //你的HTTPS域名
            'custom'    => 'static.abc.com',                //你的自定义域名
         ],
        'access_key'=> '',  //AccessKey
        'secret_key'=> '',  //SecretKey
        'bucket'    => '',  //Bucket名字
        'notify_url'=> '',  //持久化处理回调地址
    ],
],

```

然后修改`laravel-admin`的上传配置，打开`config/admin.php`找到：

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

`disk`选择上面配置的`qiniu`，`host`配置为七牛云存储的测试域名。
