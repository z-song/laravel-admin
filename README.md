# laravel-admin

`laravel-admin` is administrative interface builder for laravel which can help you build CRUD backends just with few lines of code.

[中文文档](/docs/zh.md)

`laravel-admin` based on these packages and services:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Bootstrap Markdown](http://toopay.github.io/bootstrap-markdown/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [CodeMirror](https://codemirror.net/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/admin) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

#Screenshot

![grid](https://cloud.githubusercontent.com/assets/1479100/12708148/6c4aa9fe-c8d7-11e5-94e4-c8105375a564.png)

![form](https://cloud.githubusercontent.com/assets/1479100/12708198/fc6725a8-c8d7-11e5-876f-5c4f00ded0ff.png)

# Installation

```
composer require encore/laravel-admin "dev-master"
```

Add `ServiceProvider` to `config/app.php`:

```
Encore\Admin\Providers\AdminServiceProvider::class
```

Then run these commands to finish installation:

```
php artisan vendor:publish
php artisan admin:install
```

Open `http://localhost/admin/` in your browser,and use username `admin` and password `admin` to login.

#Usage

The install path defaults to `app/Admin`.

Use `routes.php` under `app/Admin` to manage admin routes.

```php
<?php

$router = app('admin.router');

$router->get('/', function() {
    return view('admin::index');
});

$router->resources([
    'administrators' => AdministratorController::class
]);
```

Use `menu.php` to configure the menus in left sidebar：
```php
<?php

return [
    [
        'title' => 'Index',
        'url'   => '/',
        'icon'  => 'fa-bar-chart'
    ],
    [
        'title' => 'Administrators',
        'url'   => '/administrators',
        'icon'  => 'fa-tasks',
        'roles' => ['administrator'], // Roles can see this item.
    ],
    [
        'title' => 'Multilevel',
        'icon'  => 'fa-circle-o',
        'children' => [
            [
                'title' => 'Level One',
                'url'   => '/',
                'icon'  => 'fa-circle-o'
            ],
            [
                'title' => 'Level One',
                'icon'  => 'fa-circle-o',
                'children' => [
                    [
                        'title' => 'Level Two',
                        'url'   => '/',
                        'icon'  => 'fa-circle-o'
                    ]
                ]
            ],
        ]
    ],
];
```

`controllers/` is controller directory where to store admin controllers.

Language strings are stored in files within the `lang/` directory, and it will use `app.locale` configuration.

###Create controllers

If you want to create a resource controller with `User` model,you can use this command:
```
php artisan admin:make UserController --model=\\App\\User
```

It will create `UserController.php` under `app/Admin/controllers`,add a resource in `routes.php`：
```php
$router->resources([
    'users'           => UserController::class，  //add this line
    'administrators'  => AdministratorController::class
]);
```

At last add access in `menu.php`:

```php
  [
    'title' => 'Users',
    'url'   => '/users',
    'icon'  => 'fa-user'
  ],
```

So you can see the `users` resource link in the left sidebar menu.

###Admin\Grid

`Admin\Grid` is a data grid builder based on `bootstrap table`,in the controller:

```php
return Admin::grid(User::class, function(Grid $grid){

    $grid->id('ID')->sortable();

    //Use dynamic method.
    $grid->name();
    //or use column() method: $grid->column('name');

    //Add mulitiple columns.
    $grid->columns('email', 'username' ...);

    //Use related column (hasOne relation).
    $grid->column('profile.mobile', 'Mobile');
    //or use $grid->profile()->mobile('Mobile');

    //Use a callback function to display column value.
    $grid->column('profile.mobile', 'Mobile')->value(function($mobile) {
      return "+86 $mobile";
    });

    //Use sortable() method to make the column sortable.
    $grid->column('profile.age', 'Age')->sortable();
    
    // 
    $grid->column('progress')->progressBar(['danger', 'striped'], 'xs');

    //Wrapper value with a badge.
    $grid->created_at()->badge('danger');
    
    //Wrapper value with a label.
    $grid->updated_at()->label('success');

    //Set query conditions: SELECT * FROM `user` WHERE id > 20 ORDER BY updated_at DESC;
    $grid->model()->where('id', '>', '20')->orderBy('updated_at', 'desc');

    //Set 15 items per-page.
    $grid->paginate(15);

    //Set actions (edit,delete).
    $grid->actions('edit|delete');

    //Add row callback function.
    $grid->rows(function($row){
        if($row->id <= 10) {
            $row->style('color:red');
        }
      
        //Disable delete action for specify row.
        if($row->id % 3) {
            $row->action('edit');
        }
        
        //Add custom action for specify row.
        if($row->id % 2) {
            $row->actions()->add(function ($row) {
                return "<a class=\"btn btn-xs btn-danger\">btn</a>";
            });
        }
    });
    
    //Disable batch deletion.
    $grid->disableBatchDeletion();
    
    //Disable all actions.
    $grid->disableActions();

    //Add data grid filters.
    $grid->filter(function($filter){

        // sql: ... WHERE `user.name` LIKE "%$name%";
        $filter->like('name', 'name');

        // sql: ... WHERE `user.email` = $email;
        $filter->is('emial', 'Email');

        // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
        $filter->between('created_at', 'Created Time')->datetime();
    });
});

```

###Admin\Form

`Admin\Form` is a data form builder, in your controller：

```php
return Admin::form(User::class, function(Form $form){

    // $form->field(columnName [, columnName ], labelName = '');

    $form->display('id', 'ID');
    $form->text('name')->rules('required');
    $form->email('email')->rules('required|email');

    $form->password('password')->rules('required');

    //Related column (hasOne relation).
    $form->url('profile.homepage', 'Home page');

    $form->ip('last_login_ip', 'Last login ip');
    $form->datetime('last_login_at', 'Last login time');

    //All fields can set a default value.
    $form->color('color', 'Color')->default('#a34af4');

    //Code editor based on code mirror see https://codemirror.net/
    $form->code('code')->lang('ruby');
    $form->json('json');

    $form->currency('price')->symbol('￥');
    $form->number('count');
    $form->rate('rate');
    
    //You can use all Intervention Image api in image field (see http://image.intervention.io/getting_started/introduction)
    $form->image('avatar')->crop(int $width, int $height, [int $x, int $y]);
    
    $form->file('document')->rules('mimes:doc,docx,xlsx');
    $form->mobile('mobile')->format('999 9999 9999');
    $form->text('address');
    $form->date('birthday');
    $form->radio('gender')->values(['m' => 'Female', 'f'=> 'Male'])->default('m');

    //Use Google map or Tencent map.
    $form->map('latitude', 'longitude', 'Position');

    //Options see http://ionden.com/a/plugins/ion.rangeSlider/en.html.
    $form->slider('age', 'Age')->options(['max' => 50, 'min' => 20, 'step' => 1, 'postfix' => 'years old']);

    $form->display('created_at', 'Create time');
    $form->display('updated_at', 'Update time');

    $form->datetimeRange('created_at', 'profile.updated_at', 'Time line');

    //Belongs to many relation.
    $form->multipleSelect('friends')->options(User::all()->lists('name', 'id'));

    //Belongs to many relation.
    $form->checkbox('roles')->values(Role::all()->lists('display_name', 'id'));
    
    $form->switch('open')->states(['on' => 1, 'off' => 0]);
    
    //Add a divide line.
    $form->divide();

    //Has many relation, show as a list.
    $form->hasMany('comments', function(Grid $grid) {

        // Set resource path for items.
        $grid->resource('admin/article-comments');

        $grid->id('ID');
        $grid->author()->value(function($authorId){
            return User::find($authorId)->name;
        });
        $grid->email();
        $grid->content()->value(function($content) {
            return mb_strimwidth($content, 0, 40, '...');
        });
    });

    // Add saving callback function.
    $form->saving(function(Form $form) {
        if($form->password && $form->model()->password != $form->password)
        {
            $form->password = bcrypt($form->password);
        }
    });
});
```

#License

[WTFPL](http://www.wtfpl.net/)
