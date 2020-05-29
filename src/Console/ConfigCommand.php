<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ConfigCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:config {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare the difference between the admin config file and the original';

    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        $path = $this->argument('path') ?: 'config/admin.php';

        $current  = require $path;
        $original = require __DIR__.'/../../config/admin.php';

        $added   = $this->diff($current, $original);
        $removed = $this->diff($original, $current);

        if ($added->isEmpty() && $removed->isEmpty()) {
            $this->info('Configuration items have not been modified');
            return;
        }

        $this->line("The admin config file `$path`:");

        $this->printDiff('Added', $added);
        $this->printDiff('Removed', $removed, true);

        $this->line('');
        $this->comment('Please open `vendor/encore/laravel-admin/config/admin.php` to check the difference');
    }

    protected function diff(array $from, array $to)
    {
        return collect(Arr::dot($from))
            ->keys()
            ->reject(function ($key) use ($to) {
                return Arr::has($to, $key);
            });
    }

    protected function printDiff($title, $diff, $error = false)
    {
        if ($diff->isEmpty()) {
            return;
        }

        $this->line('');
        $this->comment("{$title}:");

        $diff->each(function ($key) use ($error) {
            if ($error) {
                $this->error("    {$key}");
            } else {
                $this->info("    {$key}");
            }
        });
    }
}
