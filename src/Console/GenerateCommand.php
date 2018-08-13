<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:generate {name} {--model=} {--O|output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'laravel-admin code generator';

    /**
     * @var ResourceGenerator
     */
    protected $generator;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $modelName = $this->option('model');

        $this->generator = new ResourceGenerator($modelName);

        if ($this->option('output')) {
            return $this->output($modelName);
        }

        $this->generate();
    }

    /**
     * @param string $modelName
     */
    protected function output($modelName)
    {
        $this->alert("laravel-admin resource code for model [{$modelName}]");

        $this->info($this->generator->generateGrid());
        $this->info($this->generator->generateShow());
        $this->info($this->generator->generateForm());
    }

    /**
     * @return bool
     */
    protected function generate()
    {
        $controllerName = $this->qualifyClass($this->getControllerName());
        $controllerPath = $this->getPath($controllerName);

        if ($this->alreadyExists($this->getControllerName())) {
            $this->error($controllerName.' already exists!');

            return false;
        }

        $resourceName = $this->qualifyClass($this->getResourceName(), 'Resources');
        $resourcePath = $this->getPath($resourceName);

        if ($this->alreadyExists($this->getControllerName())) {
            $this->error($resourceName.' already exists!');

            return false;
        }

        $this->makeDirectory($controllerPath);
        $this->makeDirectory($resourcePath);

        $this->files->put($controllerPath, $this->buildControllerClass($controllerName, $resourceName));
        $this->info($controllerName.' created successfully.');

        $this->files->put($resourcePath, $this->buildResourceClass($resourceName));
        $this->info($resourceName.' created successfully.');

        $this->info(' Add a route in app/Admin/routes.php');

        $this->info(sprintf("\$router->resource('%s', %s::class);", $this->argument('name'), class_basename($controllerName)));
    }

    /**
     * @param string $name
     * @param string $resource
     * @return mixed
     */
    protected function buildControllerClass($name, $resource)
    {
        $stub = __DIR__.'/stubs/controller.resource.stub';

        $stub = file_get_contents($stub);

        $code = str_replace(
            ['DummyNamespace', 'DummyResourceNamespace', 'DummyClass', 'DummyResource'],
            [$this->getNamespace($name), $resource, class_basename($name), class_basename($resource)],
            $stub
        );

        return $code;
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function buildResourceClass($name)
    {
        $stub = __DIR__.'/stubs/resource.stub';

        $stub = file_get_contents($stub);

        $code = str_replace(
            ['DummyNamespace', 'DummyModelNamespace', 'DummyClass', 'DummyModel'],
            [$this->getNamespace($name), $this->option('model'), class_basename($name), class_basename($this->option('model'))],
            $stub
        );

        $code = str_replace(
            ['DummyGrid', 'DummyShow', 'DummyForm'],
            [
                $this->indentCodes($this->generator->generateGrid()),
                $this->indentCodes($this->generator->generateShow()),
                $this->indentCodes($this->generator->generateForm())
            ],
            $code
        );

        return $code;
    }

    /**
     * @param string $code
     * @return string
     */
    protected function indentCodes($code)
    {
        $indent = str_repeat(' ',  8);

        return $indent. preg_replace("/\r\n/", "\r\n{$indent}", $code);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @param string $dir
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace, $dir = 'Controllers')
    {
        $directory = config('admin.directory');

        $namespace = ucfirst(basename($directory));

        return $rootNamespace."\\$namespace\\$dir";
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @param  string  $dir
     *
     * @return string
     */
    protected function qualifyClass($name, $dir = 'Controllers')
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\'), $dir).'\\'.$name,
            $dir
        );
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * @return string
     */
    protected function getResourceName()
    {
        return ucfirst(trim($this->argument('name'))).'Resource';
    }

    /**
     * @return string
     */
    protected function getControllerName()
    {
        return ucfirst(trim($this->argument('name'))).'Controller';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }
}
