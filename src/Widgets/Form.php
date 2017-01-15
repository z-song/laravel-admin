<?php

namespace Encore\Admin\Widgets;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Form.
 *
 * @method Field\DataField\Text           text($name, $label = '')
 * @method Field\DataField\Password       password($name, $label = '')
 * @method Field\DataField\Checkbox       checkbox($name, $label = '')
 * @method Field\DataField\Radio          radio($name, $label = '')
 * @method Field\DataField\Select         select($name, $label = '')
 * @method Field\DataField\MultipleSelect multipleSelect($name, $label = '')
 * @method Field\DataField\Textarea       textarea($name, $label = '')
 * @method Field\DataField\Hidden         hidden($name, $label = '')
 * @method Field\DataField\Id             id($name, $label = '')
 * @method Field\DataField\Ip             ip($name, $label = '')
 * @method Field\DataField\Url            url($name, $label = '')
 * @method Field\DataField\Color          color($name, $label = '')
 * @method Field\DataField\Email          email($name, $label = '')
 * @method Field\DataField\Mobile         mobile($name, $label = '')
 * @method Field\DataField\Slider         slider($name, $label = '')
 * @method Field\DataField\Map            map($latitude, $longitude, $label = '')
 * @method Field\DataField\Editor         editor($name, $label = '')
 * @method Field\DataField\File           file($name, $label = '')
 * @method Field\DataField\Image          image($name, $label = '')
 * @method Field\DataField\Date           date($name, $label = '')
 * @method Field\DataField\Datetime       datetime($name, $label = '')
 * @method Field\DataField\Time           time($name, $label = '')
 * @method Field\DataField\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DataField\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method Field\DataField\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\DataField\Number         number($name, $label = '')
 * @method Field\DataField\Currency       currency($name, $label = '')
 * @method Field\DataField\Json           json($name, $label = '')
 * @method Field\DataField\SwitchField    switch($name, $label = '')
 * @method Field\DataField\Display        display($name, $label = '')
 * @method Field\DataField\Rate           rate($name, $label = '')
 * @method Field\DataField\Divide         divide()
 * @method Field\DataField\Decimal        decimal($column, $label = '')
 * @method Field\DataField\Html           html($html)
 * @method Field\DataField\Tags           tags($column, $label = '')
 * @method Field\DataField\Icon           icon($column, $label = '')
 */
class Form implements Renderable
{
    /**
     * @var Field[]
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Form constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        $this->initFormAttributes();
    }

    /**
     * Initialize the form attributes.
     */
    protected function initFormAttributes()
    {
        $this->attributes = [
            'method'         => 'POST',
            'action'         => '',
            'class'          => 'form-horizontal',
            'accept-charset' => 'UTF-8',
            'pjax-container' => true,
        ];
    }

    /**
     * Action uri of the form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function action($action)
    {
        return $this->attribute('action', $action);
    }

    /**
     * Method of the form.
     *
     * @param string $method
     *
     * @return $this
     */
    public function method($method = 'POST')
    {
        return $this->attribute('method', strtoupper($method));
    }

    /**
     * Add form attributes.
     *
     * @param string|array $attr
     * @param string       $value
     *
     * @return $this
     */
    public function attribute($attr, $value = '')
    {
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $this->attribute($key, $value);
            }
        } else {
            $this->attributes[$attr] = $value;
        }

        return $this;
    }

    /**
     * Disable Pjax.
     *
     * @return $this
     */
    public function disablePjax()
    {
        array_forget($this->attributes, 'pjax-container');

        return $this;
    }

    /**
     * Find field class with given name.
     *
     * @param string $method
     *
     * @return bool|string
     */
    public static function findFieldClass($method)
    {
        $class = array_get(\Encore\Admin\Form::$availableFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Add a form field to form.
     *
     * @param Field $field
     *
     * @return $this
     */
    protected function pushField(Field &$field)
    {
        array_push($this->fields, $field);

        return $this;
    }

    /**
     * Get variables for render form.
     *
     * @return array
     */
    protected function getVariables()
    {
        foreach ($this->fields as $field) {
            $field->fill($this->data);
        }

        return [
            'fields'        => $this->fields,
            'attributes'    => $this->formatAttribute(),
        ];
    }

    /**
     * Format form attributes form array to html.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function formatAttribute($attributes = [])
    {
        $attributes = $attributes ?: $this->attributes;

        if ($this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        $html = [];
        foreach ($attributes as $key => $val) {
            $html[] = "$key=\"$val\"";
        }

        return implode(' ', $html) ?: '';
    }

    /**
     * Determine if form fields has files.
     *
     * @return bool
     */
    public function hasFile()
    {
        foreach ($this->fields as $field) {
            if ($field instanceof Field\DataField\File) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field|null
     */
    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $name = array_get($arguments, 0, '');

            $element = new $className($name, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }
    }

    /**
     * Render the form.
     *
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.form', $this->getVariables())->render();
    }

    /**
     * Output as string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
