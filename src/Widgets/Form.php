<?php

namespace Encore\Admin\Widgets;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Form.
 *
 * @method \Encore\Admin\Form\Field\Text           text($name, $label = '')
 * @method \Encore\Admin\Form\Field\Password       password($name, $label = '')
 * @method \Encore\Admin\Form\Field\Checkbox       checkbox($name, $label = '')
 * @method \Encore\Admin\Form\Field\Radio          radio($name, $label = '')
 * @method \Encore\Admin\Form\Field\Select         select($name, $label = '')
 * @method \Encore\Admin\Form\Field\MultipleSelect multipleSelect($name, $label = '')
 * @method \Encore\Admin\Form\Field\Textarea       textarea($name, $label = '')
 * @method \Encore\Admin\Form\Field\Hidden         hidden($name, $label = '')
 * @method \Encore\Admin\Form\Field\Id             id($name, $label = '')
 * @method \Encore\Admin\Form\Field\Ip             ip($name, $label = '')
 * @method \Encore\Admin\Form\Field\Url            url($name, $label = '')
 * @method \Encore\Admin\Form\Field\Color          color($name, $label = '')
 * @method \Encore\Admin\Form\Field\Email          email($name, $label = '')
 * @method \Encore\Admin\Form\Field\Mobile         mobile($name, $label = '')
 * @method \Encore\Admin\Form\Field\Slider         slider($name, $label = '')
 * @method \Encore\Admin\Form\Field\Map            map($latitude, $longitude, $label = '')
 * @method \Encore\Admin\Form\Field\Editor         editor($name, $label = '')
 * @method \Encore\Admin\Form\Field\File           file($name, $label = '')
 * @method \Encore\Admin\Form\Field\Image          image($name, $label = '')
 * @method \Encore\Admin\Form\Field\Date           date($name, $label = '')
 * @method \Encore\Admin\Form\Field\Datetime       datetime($name, $label = '')
 * @method \Encore\Admin\Form\Field\Time           time($name, $label = '')
 * @method \Encore\Admin\Form\Field\DateRange      dateRange($start, $end, $label = '')
 * @method \Encore\Admin\Form\Field\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method \Encore\Admin\Form\Field\TimeRange      timeRange($start, $end, $label = '')
 * @method \Encore\Admin\Form\Field\Number         number($name, $label = '')
 * @method \Encore\Admin\Form\Field\Currency       currency($name, $label = '')
 * @method \Encore\Admin\Form\Field\Json           json($name, $label = '')
 * @method \Encore\Admin\Form\Field\SwitchField    switch($name, $label = '')
 * @method \Encore\Admin\Form\Field\Display        display($name, $label = '')
 * @method \Encore\Admin\Form\Field\Rate           rate($name, $label = '')
 * @method \Encore\Admin\Form\Field\Divide         divide()
 */
class Form implements Renderable
{
    /**
     * @var string
     */
    protected $id = '';

    /**
     * @var string
     */
    protected $action = '/';

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var []Field
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $data = [];

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

        $this->id = 'form_id_'.uniqid();
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
        $this->action = $action;

        return $this;
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
        $this->method = strtoupper($method);

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
        $className = '\\Encore\\Admin\\Form\\Field\\'.ucfirst($method);

        if (class_exists($className)) {
            return $className;
        }

        if ($method == 'switch') {
            return '\\Encore\\Admin\\Form\\Field\\SwitchField';
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
            'fields' => $this->fields,
            'action' => $this->action,
            'method' => $this->method,
        ];
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
            $name = array_get($arguments, 0, ''); //[0];

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
        return view('admin::widgets.form', $this->getVariables());
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
