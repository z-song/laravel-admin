<?php

namespace Encore\Admin\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

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
        //$this->call('vendor:publish', ['--provider' => \Encore\Admin\Providers\AdminServiceProvider::class]);

        $this->publishDatabase();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function publishDatabase()
    {
//        $this->call('migrate');
//
//        $this->call('db:seed', [
//            '--class' => \Encore\Admin\Auth\Database\AdministratorsTableSeeder::class
//        ]);

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

        $this->laravel['files']->makeDirectory($this->directory, 0755, true, true);
        $this->line('<info>Admin directory was created:</info> ' . str_replace(base_path(), '', $this->directory));

        $this->createControllers();
        $this->createMenuFile();
        $this->createRoutesFile();
    }

    protected function createControllers()
    {
        $namespace = ucfirst(basename($this->directory));

        $this->laravel['files']->makeDirectory($this->directory . '/Controllers', 0755, true, true);

        $homeController = $this->directory . '/Controllers/HomeController.php';

        $contents = $this->laravel['files']->get(__DIR__ . '/stubs/HomeController.stub');
        $this->laravel['files']->put($homeController, str_replace('{namespace}', $namespace, $contents));
        $this->line('<info>HomeController file was created:</info> ' . str_replace(base_path(), '', $homeController));

        $authController = $this->directory . '/Controllers/AuthController.php';

        $contents = $this->laravel['files']->get(__DIR__ . '/stubs/AuthController.stub');
        $this->laravel['files']->put($authController, str_replace('{namespace}', $namespace, $contents));
        $this->line('<info>AuthController file was created:</info> ' . str_replace(base_path(), '', $authController));
    }

    protected function createMenuFile()
    {
        $file = $this->directory . '/menu.php';

        $contents = $this->laravel['files']->get(__DIR__ . '/stubs/menu.stub');
        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Menu file was created:</info> ' . str_replace(base_path(), '', $file));

    }

    protected function createRoutesFile()
    {
        $namespace = ucfirst(basename($this->directory));

        $file = $this->directory . '/routes.php';

        $contents = $this->laravel['files']->get(__DIR__ . '/stubs/routes.stub');
        $this->laravel['files']->put($file, str_replace('{namespace}', $namespace, $contents));
        $this->line('<info>Routes file was created:</info> ' . str_replace(base_path(), '', $file));
    }
}