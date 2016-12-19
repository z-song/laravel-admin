<?php

namespace Encore\Admin\Widgets;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Form.
 *
 * @method Field\Text           text($name, $label = '')
 * @method Field\Password       password($name, $label = '')
 * @method Field\Checkbox       checkbox($name, $label = '')
 * @method Field\Radio          radio($name, $label = '')
 * @method Field\Select         select($name, $label = '')
 * @method Field\MultipleSelect multipleSelect($name, $label = '')
 * @method Field\Textarea       textarea($name, $label = '')
 * @method Field\Hidden         hidden($name, $label = '')
 * @method Field\Id             id($name, $label = '')
 * @method Field\Ip             ip($name, $label = '')
 * @method Field\Url            url($name, $label = '')
 * @method Field\Color          color($name, $label = '')
 * @method Field\Email          email($name, $label = '')
 * @method Field\Mobile         mobile($name, $label = '')
 * @method Field\Slider         slider($name, $label = '')
 * @method Field\Map            map($latitude, $longitude, $label = '')
 * @method Field\Editor         editor($name, $label = '')
 * @method Field\File           file($name, $label = '')
 * @method Field\Image          image($name, $label = '')
 * @method Field\Date           date($name, $label = '')
 * @method Field\Datetime       datetime($name, $label = '')
 * @method Field\Time           time($name, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($name, $label = '')
 * @method Field\Currency       currency($name, $label = '')
 * @method Field\Json           json($name, $label = '')
 * @method Field\SwitchField    switch($name, $label = '')
 * @method Field\Display        display($name, $label = '')
 * @method Field\Rate           rate($name, $label = '')
 * @method Field\Divide         divide()
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
     * Ignored saving fields.
     *
     * @var array
     */
    protected $ignored = [];

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

        $this->fields = new Collection();

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
     * Get fields of this builder.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
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
        $this->fields()->push($field);

        return $this;
    }

    /**
     * Get variables for render form.
     *
     * @return array
     */
    protected function getVariables()
    {
        foreach ($this->fields() as $field) {
            $field->fill($this->data);
        }

        return [
            'fields'        => $this->fields(),
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
        foreach ($this->fields() as $field) {
            if ($field instanceof Field\File) {
                return true;
            }
        }

        return false;
    }

    /**
     * Store a new record.
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function validata()
    {
        $data = Input::all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        $this->prepare($data, null);

        DB::transaction(function () {
            $inserts = $this->prepareInsert($this->updates);
            dump($inserts);
            die;

            foreach ($inserts as $column => $value) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->saveRelation($this->relations);
        });

        if (($result = $this->complete($this->saved)) instanceof Response) {
            return $result;
        }

        if ($response = $this->ajaxResponse()) {
            return $response;
        }

        return redirect($this->resource(0));
    }

    /**
     * Get validation messages.
     *
     * @param array $input
     *
     * @return MessageBag|bool
     */
    protected function validationMessages($input)
    {
        $failedValidators = [];

        foreach ($this->fields() as $field) {
            if (!$validator = $field->getValidator($input)) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        return $message->any() ? $message : false;
    }

    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators)
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
    }

    /**
     * Prepare input data for insert or update.
     *
     * @param array    $data
     * @param callable $callback
     */
    protected function prepare($data = [], Closure $callback = null)
    {
        $this->inputs = $this->removeIgnoredFields($data);

        if ($callback instanceof Closure) {
            $callback($this);
        }

        $this->relations = $this->getRelationInputs($data);

        $updates = array_except($this->inputs, array_keys($this->relations));

        $this->updates = array_filter($updates, function ($val) {
            return !is_null($val);
        });
    }

    /**
     * Remove ignored fields from input.
     *
     * @param array $input
     *
     * @return array
     */
    protected function removeIgnoredFields($input)
    {
        array_forget($input, $this->ignored);

        return $input;
    }

    /**
     * Ignore fields to save.
     *
     * @param string|array $fields
     *
     * @return $this
     */
    public function ignore($fields)
    {
        $this->ignored = (array) $fields;

        return $this;
    }

    /**
     * Get inputs for relations.
     *
     * @param array $inputs
     *
     * @return array
     */
    protected function getRelationInputs($inputs = [])
    {
        $relations = [];

        // foreach ($inputs as $column => $value) {
        //     if (method_exists($this->model, $column)) {
        //         $relation = call_user_func([$this->model, $column]);

        //         if ($relation instanceof Relation) {
        //             $relations[$column] = $value;
        //         }
        //     }
        // }

        return $relations;
    }

    /**
     * Prepare input data for insert.
     *
     * @param $inserts
     *
     * @return array
     */
    protected function prepareInsert($inserts)
    {
        $first = current($inserts);

        if (is_array($first) && Arr::isAssoc($first)) {
            $inserts = array_dot($inserts);
        }

        foreach ($inserts as $column => $value) {
            if (is_null($field = $this->getFieldByColumn($column))) {
                unset($inserts[$column]);
                continue;
            }

            if (method_exists($field, 'prepare')) {
                $inserts[$column] = $field->prepare($value);
            }
        }

        $prepared = [];

        foreach ($inserts as $key => $value) {
            array_set($prepared, $key, $value);
        }

        return $prepared;
    }

    /**
     * Find field object by column.
     *
     * @param $column
     *
     * @return mixed
     */
    protected function getFieldByColumn($column)
    {
        return $this->fields()->first(
            function (Field $field) use ($column) {
                if (is_array($field->column())) {
                    return in_array($column, $field->column());
                }

                return $field->column() == $column;
            }
        );
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
