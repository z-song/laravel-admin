<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Facades\Admin;

class Editable
{
    /**
     * Class of form element.
     *
     * @var mixed|string
     */
    protected $class = '';

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Form element name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Type of editable.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Script for editable action.
     *
     * @var string
     */
    protected $script = '';

    /**
     * Resource url.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * Options of editable function.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Generate a new Editable instance.
     *
     * @param string $name
     * @param array  $arguments
     */
    public function __construct($name, $arguments = [])
    {
        $this->name = $name;
        $this->class = str_replace('.', '_', $name);
        $this->arguments = $arguments;

        $this->initOptions();
    }

    /**
     * Initialize options for editable.
     */
    public function initOptions()
    {
        $this->options['name'] = $this->name;
    }

    /**
     * Add options for editable.
     *
     * @param array $options
     */
    public function addOptions($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Set resource url.
     *
     * @param string $resource
     */
    public function setResource($resource = '')
    {
        $this->resource = $resource;
    }

    protected function buildEditable(array $arguments = [])
    {
        $this->type = array_get($arguments, 0, 'text');

        call_user_func_array([$this, $this->type], array_slice($arguments, 1));
    }

    /**
     * Text type editable.
     */
    public function text()
    {
    }

    /**
     * Textarea type editable.
     */
    public function textarea()
    {
    }

    /**
     * Select type editable.
     *
     * @param array $options
     */
    public function select($options = [])
    {
        $source = [];

        foreach ($options as $key => $value) {
            $source[] = [
                'value' => $key,
                'text'  => $value,
            ];
        }

        $this->addOptions(['source' => $source]);
    }

    /**
     * Date type editable.
     */
    public function date()
    {
        $this->combodate();
    }

    /**
     * Datetime type editable.
     */
    public function datetime()
    {
        $this->combodate('YYYY-MM-DD HH:mm:ss');
    }

    /**
     * Year type editable.
     */
    public function year()
    {
        $this->combodate('YYYY');
    }

    /**
     * Month type editable.
     */
    public function month()
    {
        $this->combodate('MM');
    }

    /**
     * Day type editable.
     */
    public function day()
    {
        $this->combodate('DD');
    }

    /**
     * Combodate type editable.
     *
     * @param string $format
     */
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

    /**
     * Build html for editable.
     *
     * @return string
     */
    public function html()
    {
        $this->buildEditable($this->arguments);

        $options = json_encode($this->options);

        $this->script = <<<EOT

\$('.{$this->class}-editable').editable($options);

EOT;

        Admin::script($this->script);

        $attributes = [
            'href'       => '#',
            'class'      => "{$this->class}-editable",
            'data-type'  => $this->type,
            'data-pk'    => '{pk}',
            'data-url'   => "/{$this->resource}/{pk}",
            'data-value' => '{$value}',
        ];

        $html = [];
        foreach ($attributes as $name => $attribute) {
            $html[] = "$name=\"$attribute\"";
        }

        return '<a '.implode(' ', $html).'>{$value}</a>';
    }
}
