<?php

namespace Encore\Admin\Commands;

use Encore\Admin\Facades\Admin;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the admin package';

    /**
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->call('vendor:publish', ['--provider' => \Encore\Admin\Providers\AdminServiceProvider::class]);

        $this->publishDatabase();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function publishDatabase()
    {
        $this->call('migrate', ['--path' => __DIR__  . '/../../migrations/2016_01_04_173148_create_administrators_table.php']);

        $this->call('db:seed', ['--class' => \Encore\Admin\Auth\Database\AdministratorsTableSeeder::class]);

        $this->initAdminDirectory();
    }

    /**
     * Initialize the admin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = config('admin.directory');

        if(is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        $this->makedir('/');
        $this->line('<info>Admin directory was created:</info> ' . str_replace(base_path(), '', $this->directory));

        $this->makedir('Controllers');

        $this->createHomeController();
        $this->createAuthController();
        $this->createAdministratorController();

        $this->createMenuFile();
        $this->createRoutesFile();
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createHomeController()
    {
        $homeController = $this->directory . '/Controllers/HomeController.php';
        $contents = $this->getStub('HomeController');

        $this->laravel['files']->put($homeController, str_replace('DummyNamespace', Admin::controllerNamespace(), $contents));
        $this->line('<info>HomeController file was created:</info> ' . str_replace(base_path(), '', $homeController));
    }

    /**
     * Create AuthController.
     *
     * @return void
     */
    public function createAuthController()
    {
        $authController = $this->directory . '/Controllers/AuthController.php';
        $contents = $this->getStub('AuthController');

        $this->laravel['files']->put($authController, str_replace('DummyNamespace', Admin::controllerNamespace(), $contents));
        $this->line('<info>AuthController file was created:</info> ' . str_replace(base_path(), '', $authController));
    }

    /**
     * Create AdministratorController.
     *
     * @return void
     */
    public function createAdministratorController()
    {
        $controller = $this->directory . '/Controllers/AdministratorController.php';
        $contents = $this->getStub('AdministratorController');

        $this->laravel['files']->put($controller, str_replace('DummyNamespace', Admin::controllerNamespace(), $contents));
        $this->line('<info>AdministratorController file was created:</info> ' . str_replace(base_path(), '', $controller));
    }

    /**
     * Create menu file.
     *
     * @return void
     */
    protected function createMenuFile()
    {
        $file = $this->directory . '/menu.php';

        $contents = $this->getStub('menu');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Menu file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = $this->directory . '/routes.php';

        $contents = $this->getStub('routes');
        $this->laravel['files']->put($file, str_replace('DummyNamespace', Admin::controllerNamespace(), $contents));
        $this->line('<info>Routes file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * Get stub contents.
     *
     * @param $name
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makedir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}