<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Collection;

/**
 * Class NestedForm.
 *
 * @method Field\Text           text($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 * @method Field\Id             id($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Color          color($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\Mobile         mobile($column, $label = '')
 * @method Field\Slider         slider($column, $label = '')
 * @method Field\Map            map($latitude, $longitude, $label = '')
 * @method Field\Editor         editor($column, $label = '')
 * @method Field\File           file($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 * @method Field\Date           date($column, $label = '')
 * @method Field\Datetime       datetime($column, $label = '')
 * @method Field\Time           time($column, $label = '')
 * @method Field\Year           year($column, $label = '')
 * @method Field\Month          month($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  datetimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\Currency       currency($column, $label = '')
 * @method Field\HasMany        hasMany($relationName, $callback)
 * @method Field\SwitchField    switch($column, $label = '')
 * @method Field\Display        display($column, $label = '')
 * @method Field\Rate           rate($column, $label = '')
 * @method Field\Divide         divider()
 * @method Field\Password       password($column, $label = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Html           html($html, $label = '')
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Icon           icon($column, $label = '')
 * @method Field\Embeds         embeds($column, $label = '')
 *
 */
class NestedForm
{
    const DEFAULT_KEY_NAME = '_key_';

    const REMOVE_FLAG_NAME = '_remove_';

    const REMOVE_FLAG_CLASS = 'fom-removed';

    /**
     * @var \Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    protected $relationName;

    /**
     * NestedForm key.
     *
     * @var
     */
    protected $key;

    /**
     * Fields in form.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Original data for this field.
     *
     * @var array
     */
    protected $original = [];

    /**
     * template.
     *
     * @var array
     */
    protected $template = [];

    /**
     * @var \Encore\Admin\Form
     */
    protected $form;

    /**
     * Create a new NestedForm instance.
     *
     * NestedForm constructor.
     *
     * @param $relation
     * @param null $key
     */
    public function __construct($relation, $key = null)
    {
        //    	$this->form = $form;

        $this->relationName = $relation;

        $this->key = $key;

        $this->fields = new Collection();
    }

    /**
     * Set original values for fields.
     *
     * @param array  $data
     * @param string $relatedKeyName
     *
     * @return $this
     */
    public function setOriginal($data, $relatedKeyName)
    {
        if (empty($data)) {
            return $this;
        }

        foreach ($data as $value) {
            /*
             * like $this->original[30] = [ id = 30, .....]
             */
            $this->original[$value[$relatedKeyName]] = $value;
        }

        return $this;
    }

    /**
     * Prepare for insert or update.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function prepare($input)
    {
        foreach ($input as $key => $record) {
            $this->setFieldOriginalValue($key);
            $input[$key] = $this->prepareRecord($record);
        }

        return $input;
    }

    /**
     * Set original data for each field.
     *
     * @param string $key
     *
     * @return void
     */
    protected function setFieldOriginalValue($key)
    {
        if (array_key_exists($key, $this->original)) {
            $values = $this->original[$key];

            $this->fields->each(function (Field $field) use ($values) {
                $field->setOriginal($values);
            });
        }
    }

    /**
     * Do prepare work before store and update.
     *
     * @param array $record
     *
     * @return array
     */
    protected function prepareRecord($record)
    {
        if ($record[static::REMOVE_FLAG_NAME] == 1) {
            return $record;
        }

        $prepared = [];

        /** @var Field $field */
        foreach ($this->fields as $field) {
            $columns = $field->column();

            $value = $this->fetchColumnValue($record, $columns);

            if (empty($value)) {
                continue;
            }

            if (method_exists($field, 'prepare')) {
                $value = $field->prepare($value);
            }

            if (($field instanceof \Encore\Admin\Form\Field\Hidden) || $value != $field->original()) {
                if (is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        array_set($prepared, $column, $value[$name]);
                    }
                } elseif (is_string($columns)) {
                    array_set($prepared, $columns, $value);
                }
            }
        }

        $prepared[static::REMOVE_FLAG_NAME] = $record[static::REMOVE_FLAG_NAME];

        return $prepared;
    }

    /**
     * Fetch value in input data by column name.
     *
     * @param array        $data
     * @param string|array $columns
     *
     * @return array|mixed
     */
    protected function fetchColumnValue($data, $columns)
    {
        if (is_string($columns)) {
            return array_get($data, $columns);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if (!array_has($data, $column)) {
                    continue;
                }
                $value[$name] = array_get($data, $column);
            }

            return $value;
        }

        return null;
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $this->fields->push($field);

        return $this;
    }

    /**
     * Get fields of this form.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Get relation name of this form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    public function getRelationName()
    {
        return $this->relationName;
    }

    /**
     * Fill data to all fields in form.
     *
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data)
    {
        /** @var Field $field */
        foreach ($this->fields() as $field) {
            $field->fill($data);
        }

        return $this;
    }

    /**
     * set Element class.
     *
     * @return $this
     */
    public function setElementClass()
    {
        /** @var Field $field */
        foreach ($this->fields as $field) {
            $column = $field->column();

            $class = '';

            if (is_array($column)) {
                foreach ($column as $k => $name) {
                    $class[$k] = "{$this->relationName}_$name";
                }
            } else {
                $class = "{$this->relationName}_{$field->column()}";
            }

            $field->setElementClass($class);
        }

        return $this;
    }

    /**
     * Set error key for each field in the nested form.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setErrorKey($key = null)
    {
        $key = is_null($key) ? 'new_'.static::DEFAULT_KEY_NAME : $key;

        /** @var Field $field */
        foreach ($this->fields as $field) {
            $column = $field->column();

            $errorKey = '';

            if (is_array($column)) {
                foreach ($column as $k => $name) {
                    $errorKey[$k] = "{$this->relationName}.$key.$name";
                }
            } else {
                $errorKey = "{$this->relationName}.$key.{$field->column()}";
            }

            $field->setErrorKey($errorKey);
        }

        return $this;
    }

    /**
     * Set form element name.
     *
     * @param null $key
     *
     * @return $this
     */
    public function setElementName($key = null)
    {
        $this->fields->each(function (Field $field) use ($key) {
            $column = $field->column();

            if (is_array($column)) {
                $name = array_map(function ($col) use ($key) {
                    return $this->formatElementName($col, $key);
                }, $column);
            } else {
                $name = $this->formatElementName($column, $key);
            }

            $field->setElementName($name);
        });

        return $this;
    }

    /**
     * Format form element name.
     *
     * @param string $column
     * @param string $key
     *
     * @return string
     */
    protected function formatElementName($column, $key = null)
    {
        $key = is_null($key) ? 'new_'.static::DEFAULT_KEY_NAME : $key;

        return sprintf('%s[%s][%s]', $this->relationName, $key, $column);
    }

    /**
     * Build template.
     *
     * @return $this
     */
    public function buildTemplate()
    {
        $html = '';
        $scripts = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            $html .= $field->render();  //when field render, will push $script to Admin

            if ($script = $field->getScript()) {
                $scripts[] = $field->getScript();

                /*
                 * Del the lastest script
                 */
                array_pop(Admin::$script);
            }
        }

        $this->template['html'] = $html;
        $this->template['script'] = implode("\r\n", $scripts); //separate scripts with enter

        return $this;
    }

    /**
     * Get template script.
     *
     * @return string
     */
    public function getTemplateHtml()
    {
        return $this->template['html'];
    }

    /**
     * Get template script.
     *
     * @return string
     */
    public function getTemplateScript()
    {
        return $this->template['script'];
    }

    protected function formatField(Field $field)
    {
        $column = $field->column();

        $elementName = $elementClass = $errorKey = '';

        $key = is_null($this->key) ? 'new_'.static::DEFAULT_KEY_NAME : $this->key;

        if (is_array($column)) {
            foreach ($column as $k => $name) {
                $elementName[$k] = "{$this->relationName}[$key][$name]";
                $errorKey[$k] = "{$this->relationName}.$key.$name";
                $elementClass[$k] = "{$this->relationName}_$name";
            }
        } else {
            $elementName = "{$this->relationName}[$key][{$field->column()}]";
            $errorKey = "{$this->relationName}.$key.{$field->column()}";
            $elementClass = "{$this->relationName}_{$field->column()}";
        }

        $field->setElementName($elementName)
            ->setErrorKey($errorKey)
            ->setElementClass($elementClass);

        return $field;
    }

    /**
     * Set Form.
     *
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
     * Add nested-form fields dynamically.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this|Field
     */
    public function __call($method, $arguments)
    {
        if ($className = Form::findFieldClass($method)) {
            $column = array_get($arguments, 0, '');

            $field = new $className($column, array_slice($arguments, 1));

            $field->setForm($this->form);

            $field = $this->formatField($field);

            $this->pushField($field);

            return $field;
        }

        return $this;
    }
}
