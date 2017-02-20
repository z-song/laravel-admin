<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Laravel\Dusk\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register('Encore\Admin\Providers\AdminServiceProvider');

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('app.url', 'http://localhost:9515');
        $this->app['config']->set('database.default', 'mysql');
        $this->app['config']->set('database.connections.mysql.host', 'localhost');
        $this->app['config']->set('database.connections.mysql.database', 'laravel_admin');
        $this->app['config']->set('database.connections.mysql.username', 'root');
        $this->app['config']->set('database.connections.mysql.password', '');
        $this->app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $this->app['config']->set('filesystems', require __DIR__.'/config/filesystems.php');
        $this->app['config']->set('admin', require __DIR__.'/config/admin.php');

        $this->artisan('vendor:publish');

        $this->migrate();

        //$this->artisan('admin:install');

        if (file_exists($routes = admin_path('routes.php'))) {
            require $routes;
            //$this->app['admin.router']->register();
        }

        require __DIR__.'/routes.php';

        require __DIR__.'/seeds/factory.php';
    }

    public function tearDown()
    {
        $this->rollback();

        parent::tearDown();
    }

    /**
     * run package database migrations.
     *
     * @return void
     */
    public function migrate()
    {
        foreach ($this->getMigrations() as $migration) {
            (new $migration())->up();
        }
    }

    public function rollback()
    {
        foreach ($this->getMigrations() as $migration) {
            (new $migration())->down();
        }
    }

    protected function getMigrations()
    {
        $migrations = [];

        $fileSystem = new Filesystem();

        foreach ($fileSystem->files(__DIR__.'/../migrations') as $file) {
            $fileSystem->requireOnce($file);
            $migrations[] = $this->getMigrationClass($file);
        }

        foreach ($fileSystem->files(__DIR__.'/migrations') as $file) {
            $fileSystem->requireOnce($file);
            $migrations[] = $this->getMigrationClass($file);
        }

        return $migrations;
    }

    protected function getMigrationClass($file)
    {
        $file = str_replace('.php', '', basename($file));

        $class = Str::studly(implode('_', array_slice(explode('_', $file), 4)));

        return $class;
    }
}
