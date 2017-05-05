<?php

namespace Encore\Admin\Commands;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the admin package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (!$this->confirm('Are you sure to uninstall laravel-admin?')) {
            return;
        }

        $this->removeFilesAndDirectories();
        $this->removeDatabase();

        $this->line('<info>Uninstalling laravel-admin!</info>');
    }

    /**
     * Remove files and directories.
     *
     * @return void
     */
    protected function removeFilesAndDirectories()
    {
        $this->laravel['files']->deleteDirectory(config('admin.directory'));
        $this->laravel['files']->deleteDirectory(public_path('packages/admin/'));
        $this->laravel['files']->delete(config_path('admin.php'));
    }

    /**
     * Remove database
     *
     * @return void
     */
    protected function removeDatabase()
    {
        $this->call('migrate:rollback', ['--path' => substr(__DIR__, strlen(base_path())) . '/../../migrations/']);
    }
}
