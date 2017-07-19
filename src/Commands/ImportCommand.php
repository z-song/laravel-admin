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
        $extension = $this->argument('extension');

        $psr4 = require_once base_path('vendor/composer/autoload_psr4.php');

        $namespace = '';
        foreach ($psr4 as $key => $paths) {
            foreach ((array) $paths as $path) {
                if (Str::startsWith($path, base_path('vendor/'.$extension.'/'))) {
                    $namespace = $key;
                    break;
                }
            }
        }

        if (empty($namespace)) {
            $this->error("Extension [$extension] not found");
            return;
        }

        $className = $namespace.'Extension';

        if (!class_exists($className) || !method_exists($className, 'import')) {
            $this->error("Invalid Extension [$extension]");
            return;
        }

        (new $className)->import($this);

        $this->info("Extension [$extension] imported");
    }
}
