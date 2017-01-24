<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

/**
 * Class Field.
 *
 * @method Field default($value) set field default value
 */
class Field
{
    /**
     * Element id.
     *
     * @var array|string
     */
    protected $id;

    /**
     * Element value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Field original value.
     *
     * @var mixed
     */
    protected $original;

    /**
     * Field default value.
     *
     * @var mixed
     */
    protected $default;

    /**
     * Element label.
     *
     * @var string
     */
    protected $label = '';

    /**
     * Column name.
     *
     * @var string
     */
    protected $column = '';

    /**
     * Form element name.
     *
     * @var string
     */
    protected $elementName = '';

    /**
     * Form element name.
     *
     * @var string
     */
    protected $elementClass = '';

    /**
     * Variables of elements.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Options for specify elements.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Validation rules.
     *
     * @var string
     */
    protected $rules = '';

    /**
     * Css required by this field.
     *
     * @var array
     */
    protected static $css = [];

    /**
     * Js required by this field.
     *
     * @var array
     */
    protected static $js = [];

    /**
     * Script for field.
     *
     * @var string
     */
    protected $script = '';

    /**
     * Element attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Parent form.
     *
     * @var Form
     */
    protected $form = null;

    /**
     * View for field to render.
     *
     * @var string
     */
    protected $view = '';

    /**
     * Help block.
     *
     * @var array
     */
    protected $help = [];

    /**
     * Key for errors.
     *
     * @var mixed
     */
    protected $errorKey;

    /**
     * Placeholder for this field.
     *
     * @var string|array
     */
    protected $placeholder;

    /**
     * Field constructor.
     *
     * @param $column
     * @param array $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->column = $column;
        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($column);
    }

    /**
     * Get assets required by this field.
     *
     * @return array
     */
    public static function getAssets()
    {
        return [
            'css' => static::$css,
            'js'  => static::$js,
        ];
    }

    /**
     * Format the field element id.
     *
     * @param string|array $column
     *
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
     *
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
     *
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

        return '';
    }

    /**
     * Set form element name.
     *
     * @param string $name
     *
     * @return $this
     *
     * @author Edwin Hui
     */
    public function setElementName($name)
    {
        $this->elementName = $name;

        return $this;
    }

    /**
     * Fill data to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function fill($data)
    {
         // Field value is already setted.
//        if (!is_null($this->value)) {
//            return;
//        }

        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->value[$key] = array_get($data, $column);
            }

            return;
        }

        $this->value = array_get($data, $this->column);
    }

    /**
     * Set original value to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function setOriginal($data)
    {
        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->original[$key] = array_get($data, $column);
            }

            return;
        }

        $this->original = array_get($data, $this->column);
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
     * Get or set rules.
     *
     * @param null $rules
     *
     * @return mixed
     */
    public function rules($rules = null)
    {
        if (is_null($rules)) {
            return $this->rules;
        }

        $rules = array_filter(explode('|', "{$this->rules}|$rules"));

        $this->rules = implode('|', $rules);

        return $this;
    }

    /**
     * Get field validation rules.
     *
     * @return string
     */
    protected function getRules()
    {
        return $this->rules;
    }

    /**
     * Get key for error message.
     *
     * @return string
     */
    public function getErrorKey()
    {
        return $this->errorKey ?: $this->column;
    }

    /**
     * Set key for error message.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setErrorKey($key)
    {
        $this->errorKey = $key;

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
            return is_null($this->value) ? $this->getDefault() : $this->value;
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Set default value for field.
     *
     * @param $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default value.
     *
     * @return mixed
     */
    public function getDefault()
    {
        if ($this->default instanceof \Closure) {
            return call_user_func($this->default, $this->form);
        }

        return $this->default;
    }

    /**
     * Set help block for current field.
     *
     * @param string $text
     * @param string $icon
     *
     * @return $this
     */
    public function help($text = '', $icon = 'fa-info-circle')
    {
        $this->help = compact('text', 'icon');

        return $this;
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
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|Validator
     */
    public function getValidator(array $input)
    {
        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        if (is_string($this->column)) {
            if (!array_has($input, $this->column)) {
                return false;
            }

            $input = $this->sanitizeInput($input, $this->column);

            $rules[$this->column] = $fieldRules;
            $attributes[$this->column] = $this->label;
        }

        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                if (!array_key_exists($column, $input)) {
                    continue;
                }
                $input[$column.$key] = array_get($input, $column);
                $rules[$column.$key] = $fieldRules;
                $attributes[$column.$key] = $this->label."[$column]";
            }
        }

        return Validator::make($input, $rules, [], $attributes);
    }

    /**
     * Sanitize input data.
     *
     * @param array  $input
     * @param string $column
     *
     * @return array
     */
    protected function sanitizeInput($input, $column)
    {
        if ($this instanceof Field\MultipleSelect) {
            $value = array_get($input, $column);
            array_set($input, $column, array_filter($value));
        }

        return $input;
    }

    /**
     * Add html attributes to elements.
     *
     * @param array|string $attribute
     * @param mixed        $value
     *
     * @return $this
     */
    public function attribute($attribute, $value = null)
    {
        if (is_array($attribute)) {
            $this->attributes = array_merge($this->attributes, $attribute);
        } else {
            $this->attributes[$attribute] = (string) $value;
        }

        return $this;
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
     * Format the field attributes.
     *
     * @return string
     */
    protected function formatAttributes()
    {
        $html = [];

        foreach ($this->attributes as $name => $value) {
            $html[] = $name.'="'.e($value).'"';
        }

        return implode(' ', $html);
    }

    /**
     * Set form element class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setElementClass($class)
    {
        $this->elementClass = $class;

        return $this;
    }

    /**
     * Get element class.
     *
     * @return string
     */
    protected function getElementClass()
    {
        if (!$this->elementClass) {
            $name = $this->elementName ?: $this->formatName($this->column);

            $this->elementClass = str_replace(['[', ']'], '_', $name);
        }

        return $this->elementClass;
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['id'] = $this->id;
        $this->variables['name'] = $this->elementName ?: $this->formatName($this->column);
        $this->variables['value'] = $this->value();
        $this->variables['label'] = $this->label;
        $this->variables['column'] = $this->column;
        $this->variables['attributes'] = $this->formatAttributes();
        $this->variables['help'] = $this->help;
        $this->variables['class'] = $this->getElementClass();
        $this->variables['errorKey'] = $this->getErrorKey();
        $this->variables['placeholder'] = $this->getPlaceholder();

        return $this->variables;
    }

    /**
     * Get view of this field.
     *
     * @return string
     */
    public function getView()
    {
        if (!empty($this->view)) {
            return $this->view;
        }

        $class = explode('\\', get_called_class());

        return 'admin::form.'.strtolower(end($class));
    }

    public function getScript()
    {
        return $this->script;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        Admin::script($this->script);

        return view($this->getView(), $this->variables());
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
            return $this->setDefault(array_get($arguments, 0));
        }
    }
}
