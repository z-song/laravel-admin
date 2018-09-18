<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Editable extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Type of editable.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Options of editable function.
     *
     * @var array
     */
    protected $options = [];

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
     * Text type editable.
     */
    public function text()
    {
    }

    /**
     * Textarea type editable.
     */
    public function textarea($width = 200, $rows = 7, $class = '')
    {

        $this->addOptions([
            'tpl'  => "<textarea style='width: {$width}px' class='$class'></textarea>",
            'rows' => $rows
        ]);
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

    public function select2($options = [])
    {
        $source = [];

        foreach ($options as $key => $value) {
            $source[] = [
                'id'   => $key,
                'text' => $value,
            ];
        }
      //more options  http://vitalets.github.io/x-editable/docs.html#select2
        $this->addOptions([
            'source'        => $source,
            'viewseparator' => ',',
            'select2'       => [
//                'multiple'           => false,
//                'placeholder'        => 'Select Country',
//                'minimumInputLength' => 1

            ]
        ]);
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
            'format'     => $format,
            'viewformat' => $format,
            'template'   => $format,
            'combodate'  => [
                'maxYear' => 2035,
            ],
        ]);
    }

    protected function buildEditableOptions(array $arguments = [])
    {
        $this->type = array_get($arguments, 0, 'text');

        call_user_func_array([
            $this,
            $this->type
        ], array_slice($arguments, 1));
    }

    public function display()
    {
        $this->options['name'] = $column = $this->column->getName();

        $class = 'grid-editable-' . str_replace([
                '.',
                '#',
                '[',
                ']'
            ], '-', $column);

        $this->buildEditableOptions(func_get_args());

        $options = json_encode($this->options);

        Admin::script("$('.$class').editable($options);");

        $attributes = [
            'href'       => '#',
            'class'      => "$class",
            'data-type'  => $this->type,
            'data-pk'    => "{$this->getKey()}",
            'data-url'   => "{$this->grid->resource()}/{$this->getKey()}",
            'data-value' => "{$this->value}",
        ];

        $attributes = collect($attributes)->map(function ($attribute, $name) {
            return "$name='$attribute'";
        })->implode(' ');

        $html = $this->type === 'select' ? '' : $this->value;

        return "<a $attributes>{$html}</a>";
    }
}
