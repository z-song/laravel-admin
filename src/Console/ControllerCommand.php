<?php

namespace Encore\Admin\Console;

class ControllerCommand extends MakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:controller {model}
        {--title=}
        {--stub= : Path to the custom stub file. }
        {--namespace=}
        {--O|output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make admin controller from giving model';

    /**
     * @return array|string|null
     */
    protected function getModelName()
    {
        return $this->argument('model');
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    protected function getControllerName()
    {
        $name = (new \ReflectionClass($this->modelName))->getShortName();

        return $name.'Controller';
    }
}
