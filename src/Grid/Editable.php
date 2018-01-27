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

    protected $options = [];

    public function __construct($name, $arguments = [])
    {
        $this->name = $name;
        $this->class = str_replace('.', '_', $name);
        $this->arguments = $arguments;

        $this->initOptions();
    }

    public function initOptions()
    {
        $this->options['name'] = $this->name;
    }

    public function addOptions($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function setResource($resource = '')
    {
        $this->resource = $resource;
    }

    protected function buildEditable(array $arguments = [])
    {
        $this->type = array_get($arguments, 0, 'text');

        call_user_func_array([$this, $this->type], array_slice($arguments, 1));
    }

    public function text()
    {

    }

    public function textarea()
    {

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

        $this->addOptions(['source' => $source]);
    }

    public function date()
    {
        $this->combodate();
    }

    public function datetime()
    {
        $this->combodate('YYYY-MM-DD HH:mm:ss');
    }

    public function year()
    {
        $this->combodate('YYYY');
    }

    public function month()
    {
        $this->combodate('MM');
    }

    public function day()
    {
        $this->combodate('DD');
    }

    public function combodate($format = 'YYYY-MM-DD')
    {
        $this->type = 'combodate';

        $this->addOptions([
            'format'        => $format,
            'viewformat'    => $format,
            'template'      => $format,
            'combodate'     => [
                'maxYear' => 2035,
            ],
        ]);
    }

    public function html()
    {
        $this->buildEditable($this->arguments);

        $options = json_encode($this->options);

        $this->script = <<<EOT

\$('.{$this->class}-editable').editable($options);

EOT;

        Admin::script($this->script);

        $attributes = [
            'href'      => '#',
            'class'     => "{$this->class}-editable",
            'data-type' => $this->type,
            'data-pk'   => '{pk}',
            'data-url'  => "/{$this->resource}/{pk}",
            'data-value'=> '{$value}',
        ];

        $html = [];
        foreach ($attributes as $name => $attribute) {
            $html[] = "$name=\"$attribute\"";
        }

        return '<a '. join(' ', $html) .'>{$value}</a>';
    }
}
