<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NestedForm
{
    const UPDATE_KEY_NAME_OLD = 'old';

    const UPDATE_KEY_NAME_NEW = 'new';

    const DEFAULT_KEY_NAME = '_key_';

    const REMOVE_FLAG_NAME = '_remove_';

    const REMOVE_FLAG_CLASS = 'fom-removed';

    /**
     * @var \Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    protected $relationName;

    /**
     * Fields in form.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Scripts of form.
     *
     * @var array
     */
    protected $scripts = [];

    /**
     * Original data for this field.
     *
     * @var array
     */
    protected $original = [];

    /**
     * Create a new NestedForm instance.
     *
     * @param $relation
     */
    public function __construct($relation)
    {
        $this->relationName = $relation;

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
	        /**
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
        $values = $this->original[$key];

        $this->fields->each(function (Field $field) use ($values) {
            $field->setOriginal($values);
        });
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

        foreach ($this->fields as $field) {
            $columns = $field->column();

            $value = $this->fetchColumnValue($record, $columns);

            if (method_exists($field, 'prepare')) {
                $value = $field->prepare($value);
            }

            if ($value != $field->original()) {
                if (is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        array_set($prepared, $column, $value[$name]);
                    }
                } elseif (is_string($columns)) {
                    array_set($prepared, $columns, $value);
                }
            }
        }

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
        foreach ($this->fields() as $field) {
            $field->fill($data);
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
    public function setErrorKey($key)
    {
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
     * @param null   $key
     *
     * @return $this
     */
    protected function setElementName( $key = null)
    {
        $this->fields->each(function (Field $field) use ( $key) {
            $column = $field->column();

            if (is_array($column)) {
                $name = array_map(function ($col) use ( $key) {
                    return $this->formatElementName( $col, $key);
                }, $column);
            } else {
                $name = $this->formatElementName( $column, $key);
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
        $key = is_null($key) ? static::DEFAULT_KEY_NAME : $key;

        return sprintf('%s[%s][%s]', $this->relationName, $key, $column);
    }


    /**
     * Get form html without script.
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = '';

        foreach ($this->fields() as $field) {
            $html .= $field->render();  //when field render, will push $script to Admin

            if ($script = $field->getScript()) {
                $this->scripts[] = $field->getScript();

	            /**
	             * Del the lastest script
	             */
                array_pop(Admin::$script);
            }
        }

        return $html;
    }

    /**
     * Get form script as string.
     *
     * @return string
     */
    public function getFormScript()
    {
	    /**
	     * separate scripts with enter
	     */
        return implode("\r\n", $this->scripts);
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

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }

        return $this;
    }
}
