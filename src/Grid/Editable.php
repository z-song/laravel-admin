<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Facades\Admin;

class Editable
{
    protected $class = '';

    protected $arguments = [];

    protected $name = '';

    protected $type = '';

    protected $script = '';

    protected $resource = '';

    public function __construct($name, $arguments = [])
    {
        $this->name = $name;
        $this->class = str_replace('.', '_', $name);
        $this->arguments = $arguments;
    }

    public function setResource($resource = '')
    {
        $this->resource = $resource;
    }

    public function buildEditable(array $arguments = [])
    {
        $this->type = array_get($arguments, 0, 'text');

        call_user_func_array([$this, $this->type], array_slice($arguments, 1));
    }

    public function text()
    {
        $this->script = <<<EOT
\$('.{$this->class}-editable').editable({name:'{$this->name}'});
EOT;

    }

    public function textarea()
    {
        $this->script = <<<EOT
\$('.{$this->class}-editable').editable({name:'{$this->name}'});
EOT;
    }
    
    public function select($options = [])
    {
        $source = [];

        foreach ($options as $key => $value) {
            $source[] = [
                'value' => $key,
                'text'  => $value
            ];
        }

        $source = json_encode($source);

        $this->script = <<<EOT

\$('.{$this->class}-editable').editable({
        source: $source
    });
EOT;
    }

    public function html()
    {
        $this->buildEditable($this->arguments);

        Admin::script($this->script);

        return "<a href=\"#\" class=\"{$this->class}-editable\" data-type=\"{$this->type}\" data-pk=\"{pk}\" data-url=\"/{$this->resource}/{pk}\">{\$value}</a>";
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getScript()
    {
        return $this->script;
    }
}
