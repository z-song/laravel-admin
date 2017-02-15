<?php

namespace Encore\Admin\Scaffold;

use Illuminate\Support\Str;

class ModelCreator
{
    protected $tableName;

    protected $name;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    public function __construct($tableName, $name, $files = null)
    {
        $this->tableName = $tableName;

        $this->name = $name;

        $this->files = $files ?: app('files');
    }

    protected function populateStub()
    {
        $stub = $this->files->get($this->getStub());
    }

    public function create($keyName = 'id', $useTimestamps = true, $useSoftDeletes = false)
    {
        if (Str::endsWith($this->name, '\\')) {
            $this->name = trim($this->name, '\\'). '\\' . ucfirst(Str::singular($this->tableName));
        }

        $namespace = substr($this->name, 0, strrpos($this->name, '\\'));



        $this->populateStub();

        dd($namespace, $this->name, $this->tableName, $stub);
    }

    public function getStub()
    {
        return __DIR__.'/stubs/model.stub';
    }
}
