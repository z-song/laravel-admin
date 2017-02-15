<?php

namespace Encore\Admin\Scaffold;

use Illuminate\Support\Str;

class ModelCreator
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $tableName;

    /**
     * Model name.
     *
     * @var string
     */
    protected $name;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * ModelCreator constructor.
     *
     * @param string $tableName
     * @param string $name
     * @param null $files
     */
    public function __construct($tableName, $name, $files = null)
    {
        $this->tableName = $tableName;

        $this->name = $name;

        $this->files = $files ?: app('files');
    }

    /**
     * Create a new migration file.
     *
     * @param string $keyName
     * @param bool|true $timestamps
     * @param bool|false $softDeletes
     *
     * @return string
     *
     * @throws \Exception
     */
    public function create($keyName = 'id', $timestamps = true, $softDeletes = false)
    {
        $path = $this->getpath($this->name);

        if ($this->files->exists($path)) {
            throw new \Exception("Model [$this->name] already exists!");
        }

        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceClass($stub, $this->name)
            ->replaceNamespace($stub, $this->name)
            ->replaceSoftDeletes($stub, $softDeletes)
            ->replaceTable($stub, $this->name)
            ->replaceTimestamp($stub, $timestamps)
            ->replacePrimaryKey($stub, $keyName)
            ->replaceSpace($stub);

        $this->files->put($path, $stub);

        return $path;
    }

    /**
     * Get path for migration file.
     *
     * @param string $name
     *
     * @return string
     */
    public function getPath($name)
    {
        $segments = explode('\\', $name);

        array_shift($segments);

        return app_path(join('/', $segments)).'.php';
    }

    /**
     * Get namespace of giving class full name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace class dummy.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceClass(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace('DummyClass', $class, $stub);

        return $this;
    }

    /**
     * Replace namespace dummy.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            'DummyNamespace', $this->getNamespace($name), $stub
        );

        return $this;
    }

    /**
     * Replace soft-deletes dummy.
     *
     * @param string $stub
     * @param bool   $softDeletes
     *
     * @return $this
     */
    protected function replaceSoftDeletes(&$stub, $softDeletes)
    {
        $import = $use = '';

        if ($softDeletes) {
            $import = "use Illuminate\\Database\\Eloquent\\SoftDeletes;\n";
            $use = "use SoftDeletes;\n";
        }

        $stub = str_replace(['DummyImportSoftDeletesTrait', 'DummyUseSoftDeletesTrait'], [$import, $use], $stub);

        return $this;
    }

    /**
     * Replace primarykey dummy.
     *
     * @param string $stub
     * @param string $keyName
     *
     * @return $this
     */
    protected function replacePrimaryKey(&$stub, $keyName)
    {
        $modelKey = $keyName == 'id' ? '' : "protected \$primaryKey = '$keyName';\n";

        $stub = str_replace('DummyModelKey', $modelKey, $stub);

        return $this;
    }

    /**
     * Replace Table name dummy.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceTable(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $table = Str::plural(strtolower($class)) !== $this->tableName ? "protected \$table = '$this->tableName';\n" : '';

        $stub  = str_replace('DummyModelTable', $table, $stub);

        return $this;
    }

    /**
     * Replace timestamps dummy.
     *
     * @param string $stub
     * @param bool   $timestamps
     *
     * @return $this
     */
    protected function replaceTimestamp(&$stub, $timestamps)
    {
        $useTimestamps = $timestamps ? '' : "public \$timestamps = false;\n";

        $stub = str_replace('DummyTimestamp', $useTimestamps, $stub);

        return $this;
    }

    /**
     * Replace spaces.
     *
     * @param string $stub
     *
     * @return mixed
     */
    public function replaceSpace($stub)
    {
        return str_replace(["\n\n\n", "\n    \n"], ["\n\n", ""], $stub);
    }

    /**
     * Get stub path of model.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__.'/stubs/model.stub';
    }
}
