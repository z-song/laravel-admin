<?php

use Encore\Admin\AdminServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Tests\Models\Profile;
use Tests\Models\Tag;
use Tests\Models\User;

class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost:8000';

    /**
     * Boots the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->booting(static function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Admin', \Encore\Admin\Facades\Admin::class);
        });

        $app->make(Kernel::class)->bootstrap();

        $app->register(AdminServiceProvider::class);

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $adminConfig = require __DIR__.'/config/admin.php';

        $this->app['config']->set('database.default', 'mysql');
        $this->app['config']->set('database.connections.mysql.host', env('MYSQL_HOST', 'localhost'));
        $this->app['config']->set('database.connections.mysql.database', env('MYSQL_DATABASE', 'laravel_admin_test'));
        $this->app['config']->set('database.connections.mysql.username', env('MYSQL_USER', 'root'));
        $this->app['config']->set('database.connections.mysql.password', env('MYSQL_PASSWORD', ''));
        $this->app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $this->app['config']->set('filesystems', require __DIR__.'/config/filesystems.php');
        $this->app['config']->set('admin', $adminConfig);

        foreach (Arr::dot(Arr::get($adminConfig, 'auth'), 'auth.') as $key => $value) {
            $this->app['config']->set($key, $value);
        }

        $this->artisan('vendor:publish', ['--provider' => AdminServiceProvider::class]);

        Schema::defaultStringLength(191);

        $this->artisan('admin:install');

        $this->migrateTestTables();

        if (file_exists($routes = admin_path('routes.php'))) {
            require $routes;
        }

        require __DIR__.'/routes.php';

        User::factory()->create();
        Profile::factory()->create()->each(static function (Profile $profile) {
        	$profile->user()->associate(User::factory()->make())->save();
		});
        Tag::factory()->create();

//        \Encore\Admin\Admin::$css = [];
//        \Encore\Admin\Admin::$js = [];
//        \Encore\Admin\Admin::$script = [];
    }

    protected function tearDown(): void
    {
        (new CreateAdminTables())->down();

        (new CreateTestTables())->down();

        DB::select("DELETE FROM `migrations` WHERE `migration` = '2016_01_04_173148_create_admin_tables'");

        parent::tearDown();
    }

    /**
     * run package database migrations.
     *
     * @return void
     */
    public function migrateTestTables(): void
    {
        $fileSystem = new Filesystem();

        $fileSystem->requireOnce(__DIR__.'/migrations/2016_11_22_093148_create_test_tables.php');

        (new CreateTestTables())->up();
    }
}
