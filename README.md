# laravel-admin

`laravel-admin` is administrative interface builder for laravel which can help you build CRUD backends just with few lines of code.

`laravel-admin` based on these packages and services:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Bootstrap Markdown](http://toopay.github.io/bootstrap-markdown/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Tencent map](http://lbs.qq.com/)

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/admin) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

#Screenshot

![grid](https://cloud.githubusercontent.com/assets/1479100/12708148/6c4aa9fe-c8d7-11e5-94e4-c8105375a564.png)

![form](https://cloud.githubusercontent.com/assets/1479100/12708198/fc6725a8-c8d7-11e5-876f-5c4f00ded0ff.png)

# Installation

```
//laravel 5.1
composer require encore/laravel-admin "1.1.*"

//laravel 5.2
composer require encore/laravel-admin "1.2.*"
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
        'icon'  => 'fa-tasks'
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

    //use dynamic method.
    $grid->name();
    //or use column() method: $grid->column('name');
    
    //add mulitiple columns.
    $grid->columns('email', 'username' ...);
    
    //use related column (hasOne relation).
    $grid->column('profile.mobile', 'Mobile');
    //or use $grid->profile()->mobile('Mobile');
    
    //use a callback function to display column value.
    $grid->column('profile.mobile', 'Mobile')->value(function($mobile) {
      return "+86 $mobile";
    });
    
    //use sortable() method to make the column sortable.
    $grid->column('profile.age', 'Age')->sortable();
    
    $grid->created_at();
    $grid->updated_at();

    //set query conditions: SELECT * FROM `user` WHERE id > 20 ORDER BY updated_at DESC;
    $grid->model()->where('id', '>', '20')->orderBy('updated_at', 'desc');

    //set 15 items per-page.
    $grid->paginate(15);

    //set actions (show,edit,delete).
    $grid->actions('show|edit|delete');

    //add row callback function.
    $grid->rows(function($row){
      if($row->id <= 10) {
        $row->style('color:red');
      }
    });
    
    //add data grid filters.
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

    $form->options(['title' => 'Edit user']);
    
    $form->id('id', 'ID');
    $form->text('name')->rules('required');
    $form->email('email')->rules('required|email');
    
    $form->password('password')->rules('required');
    
    // has one relation, user has one profile
    $form->url('profile.homepage', 'Home page');

    $form->ip('profile.last_login_ip', 'Last login ip');
    $form->datetime('profile.last_login_at', 'Last login time');
    
    // Add default value.
    $form->color('profile.color', 'Color')->default('#a34af4');

    $form->image('profile.avatar')/*->size(300, 300)*/;
    $form->file('profile.document')->rules('mimes:doc,docx,xlsx');
    $form->mobile('profile.mobile');
    $form->text('profile.address');
    $form->date('profile.birthday');
    $form->radio('profile.gender')->values(['m' => 'Female', 'f'=> 'Male'])->default('m');

    // see http://lbs.qq.com/
    $form->map('profile.lat', 'profile.lng', 'Position');
    
    // see http://ionden.com/a/plugins/ion.rangeSlider/en.html
    $form->slider('profile.age', 'Age')->options(['max' => 50, 'min' => 20, 'step' => 1, 'postfix' => 'years old']);

    $form->datetime('created_at', 'Create time');
    $form->datetime('updated_at', 'Update time');

    $form->datetimeRange('profile.created_at', 'profile.updated_at', 'Time line');

    // belongs to many relation
    $form->multipleSelect('friends')->options(User::all()->lists('name', 'id'));
    
    // belongs to many relation
    $form->checkbox('roles')->values(Role::all()->lists('display_name', 'id'));
    
    // Add saving callback function.
    $form->saving(function($form) {
        $form->password = bcrypt($form->password);
    });
});
```

#License

[WTFPL](http://www.wtfpl.net/)
