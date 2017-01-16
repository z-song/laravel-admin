<?php

namespace Encore\Admin\Field;

use Encore\Admin\Form;
use Encore\Admin\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Field.
 *
 * @method Field default($value) set field default value
 */
class DataField extends Field
{

    /**
     * Field default value.
     *
     * @var mixed
     */
    protected $default;

    /**
     * Options for specify elements.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Placeholder for this field.
     *
     * @var string|array
     */
    protected $placeholder;




    /**
     * Set the field options.
     *
     * @param array $options
     *
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
     * Set or get value of the field.
     *
     * @param null $value
     *
     * @return mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return is_null($this->value) ? $this->default : $this->value;
        }

        $this->value = $value;

        return $this;
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
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|Validator
     */
    public function getValidator(array $input)
    {
        $input = $this->sanitizeInput($input, $this->column);

        return parent::getValidator($input);
    }

    /**
     * Sanitize input data. Clear empty value
     *
     * @param array  $input
     * @param string $column
     *
     * @return array
     */
    protected function sanitizeInput($input, $column)
    {
        if ($this instanceof DataField\MultipleSelect) {
            $value = array_get($input, $column);
            array_set($input, $column, array_filter($value));
        }

        return $input;
    }

    /**
     * Set the field as readonly mode.
     *
     * @return Field
     */
    public function readOnly()
    {
        return $this->attribute('disabled', true);
    }

    /**
     * Set field placeholder.
     *
     * @param string $placeholder
     *
     * @return Field
     */
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder.
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder ?: trans('admin::lang.input').' '.$this->label;
    }


    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables = parent::variables();

        $this->variables['value'] = $this->value();
        $this->variables['placeholder'] = $this->getPlaceholder();

        return $this->variables;
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if ($method === 'default') {
            $this->default = $arguments[0];

            return $this;
        }
    }
}
