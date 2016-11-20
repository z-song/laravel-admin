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
     * Install directory.
     *
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
        $this->publishDatabase();

        $this->initAdminDirectory();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function publishDatabase()
    {
        $this->call('migrate', ['--path' => str_replace(base_path(), '', __DIR__).'/../../migrations/']);

        $this->call('db:seed', ['--class' => \Encore\Admin\Auth\Database\AdminTablesSeeder::class]);
    }

    /**
     * Initialize the admin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = config('admin.directory');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        $this->makeDir('/');
        $this->line('<info>Admin directory was created:</info> '.str_replace(base_path(), '', $this->directory));

        $this->makeDir('Controllers');

        $this->createHomeController();
        $this->createExampleController();
        //$this->createAuthController();
        //$this->createAdministratorController();

        //$this->createMenuFile();
        $this->createBootstrapFile();
        $this->createRoutesFile();

        //$this->copyLanguageFiles();
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createHomeController()
    {
        $homeController = $this->directory.'/Controllers/HomeController.php';
        $contents = $this->getStub('HomeController');

        $this->laravel['files']->put(
            $homeController,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line('<info>HomeController file was created:</info> '.str_replace(base_path(), '', $homeController));
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createExampleController()
    {
        $exampleController = $this->directory.'/Controllers/ExampleController.php';
        $contents = $this->getStub('ExampleController');

        $this->laravel['files']->put(
            $exampleController,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line('<info>ExampleController file was created:</info> '.str_replace(base_path(), '', $exampleController));
    }

    /**
     * Create AuthController.
     *
     * @return void
     */
    public function createAuthController()
    {
        $authController = $this->directory.'/Controllers/AuthController.php';
        $contents = $this->getStub('AuthController');

        $this->laravel['files']->put(
            $authController,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line('<info>AuthController file was created:</info> '.str_replace(base_path(), '', $authController));
    }

    /**
     * Create AdministratorController.
     *
     * @return void
     */
    public function createAdministratorController()
    {
        $controller = $this->directory.'/Controllers/AdministratorController.php';
        $contents = $this->getStub('AdministratorController');

        $this->laravel['files']->put(
            $controller,
            str_replace('DummyNamespace', Admin::controllerNamespace(), $contents)
        );
        $this->line(
            '<info>AdministratorController file was created:</info> '.str_replace(base_path(), '', $controller)
        );
    }

    /**
     * Create menu file.
     *
     * @return void
     */
    protected function createMenuFile()
    {
        $file = $this->directory.'/menu.php';

        $contents = $this->getStub('menu');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Menu file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createBootstrapFile()
    {
        $file = $this->directory.'/bootstrap.php';

        $contents = $this->getStub('bootstrap');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Bootstrap file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = $this->directory.'/routes.php';

        $contents = $this->getStub('routes');
        $this->laravel['files']->put($file, str_replace('DummyNamespace', Admin::controllerNamespace(), $contents));
        $this->line('<info>Routes file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Copy language files to admin directory.
     *
     * @return void
     */
    protected function copyLanguageFiles()
    {
        $this->laravel['files']->copyDirectory(__DIR__.'/../../lang/', "{$this->directory}/lang/");
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}
