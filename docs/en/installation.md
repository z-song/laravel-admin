# Installation

First, install laravel, and make sure that the database connection settings are correct.

```
Laravel 5.1
composer require encore/laravel-admin "1.1.*"

Laravel 5.2
composer require encore/laravel-admin "1.2.*"

Laravel 5.3
composer require encore/laravel-admin "1.3.*"

Laravel 5.4
composer require encore/laravel-admin "1.4.x-dev"

```

In`config/app.php`add`ServiceProvider`:

```
Encore\Admin\Providers\AdminServiceProvider::class
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --tag=laravel-admin
```
After run command you can find config file in `config/admin.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install. 
```
php artisan admin:install
```

Open `http://localhost/admin/` in browser,use username `admin` and password `admin` to login.

## Generated files

After the installation is complete, the following files are generated in the project directory:

### Configuration file

After the installation is complete，all configurations are in the `config/admin.php` file.

### Admin files
After install,you can find directory`app/Admin`,and then most of our develop work is under this directory.

```
app/Admin
├── Controllers
│   ├── ExampleController.php
│   └── HomeController.php
├── bootstrap.php
└── routes.php
```

`app/Admin/routes.php` is used to define routes.

`app/Admin/bootstrap.php` is bootstrapper for laravel-admin, more usages see comments inside it.

The `app/Admin/Controllers` directory is used to store all the controllers, The `HomeController.php` file under this directory is used to handle home request of admin,The `ExampleController.php` file is a controller example.

### Static assets

The front-end static files are in the `/public/packages/admin` directory.