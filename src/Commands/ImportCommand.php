<?php

namespace Encore\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:import {extension}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a extension';

    /**
     * Execute the console command.
     *
     * @return void
     */

    public function handle()
    {
        $className = $this->argument('extension');

        if (!class_exists($className) || !method_exists($className, 'import')) {
            $this->error("Invalid Extension [$className]");
            return;
        }

        call_user_func([$className, 'import'], $this);

        $this->info("Extension [$className] imported");
    }
}
