<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;

class Field
{

    protected $id;

    protected $value;

    protected $original;

    protected $default;

    protected $label    = '';

    protected $column   = '';

    protected $variables = [];

    protected $options  = [];

    protected $rules    = '';

    protected $css      = [];

    protected $js       = [];

    protected $script   = '';

    protected $attributes = [];

    protected $form = null;

    public function __construct($column, $arguments = [])
    {
        $this->column = $column;
        $this->label  = $this->formatLabel($arguments);
        $this->id     = $this->formatId($column);
    }

    /**
     * Format the field element id.
     *
     * @param string|array $column
     * @return string|array
     */
    protected function formatId($column)
    {
        return str_replace('.', '_', $column);
    }

    /**
     * Format the label value.
     *
     * @param array $arguments
     * @return string
     */
    protected function formatLabel($arguments = [])
    {
        $column = is_array($this->column) ? current($this->column) : $this->column;

        $label = isset($arguments[0]) ? $arguments[0] : ucfirst($column);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Format the name of the field.
     *
     * @param string $column
     * @return array|mixed|string
     */
    protected function formatName($column)
    {
        if (is_string($column)) {

            $name = explode('.', $column);

            if (count($name) == 1) {
                return $name[0];
            }

            $html = array_shift($name);
            foreach ($name as $piece) {
                $html .= "[$piece]";
            }

            return $html;
        }

        if (is_array($this->column)) {
            $names = [];
            foreach ($this->column as $key => $name) {
                $names[$key] = $this->formatName($name);
            }

            return $names;
        }
    }

    /**
     * Fill data to the field.
     *
     * @param $data
     * @return void
     */
    public function fill($data)
    {
        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->value[$key] = Arr::get($data, $column);
            }

            return;
        }

        $this->value = Arr::get($data, $this->column);
    }

    /**
     * Set original value to the field.
     *
     * @param $data
     * @return void
     */
    public function setOriginal($data)
    {
        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->original[$key] = Arr::get($data, $column);
            }

            return;
        }

        $this->original = Arr::get($data, $this->column);
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set the field options.
     *
     * @param array $options
     * @return $this
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Get or set rules.
     *
     * @param null $rules
     * @return string
     */
    public function rules($rules = null)
    {
        if (is_null($rules)) {
            return $this->rules;
        }

        $rules = array_filter(explode('|', "{$this->rules}|$rules"));

        $this->rules = join('|', $rules);
    }

    /**
     * Set or get value of the field.
     *
     * @param null $value
     * @return mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return is_null($this->value) ? $this->default : $this->value;
        }

        $this->value = $value;
    }

    /**
     * Get column of the field.
     *
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * Get label of the field.
     *
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * Get original value of the field.
     *
     * @return mixed
     */
    public function original()
    {
        return $this->original;
    }

    /**
     * Set the field as readonly mode.
     */
    public function readOnly()
    {
        $this->attributes['disabled'] = true;
    }

    /**
     * Format the field attributes.
     *
     * @return string
     */
    protected function formatAttributes()
    {
        $html = [];

        foreach ($this->attributes as $name => $value) {
            $html[] = "$name=\"$value\"";
        }

        return join(' ', $html);
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['id']      = $this->id;
        $this->variables['name']    = $this->formatName($this->column);
        $this->variables['value']   = $this->value();
        $this->variables['label']   = $this->label;
        $this->variables['column']  = $this->column;
        $this->variables['attributes']  = $this->formatAttributes();

        return $this->variables;
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    public function getView()
    {
        $class = explode('\\', get_called_class());

        return 'admin::form.' . strtolower(end($class));
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        Admin::js($this->js);
        Admin::css($this->css);
        Admin::script($this->script);

        return view($this->getView(), $this->variables());
    }

    public function __call($method, $arguments)
    {
        if ($method === 'default') {
            $this->default = $arguments[0];
            return $this;
        }
    }
}
