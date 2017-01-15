<?php

namespace Encore\Admin\Form;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NestedForm3
{
    const DEFAULT_KEY_NAME = '_key_';

    /**
     * Relation remove flag
     */
    const REMOVE_FLAG_NAME = '_remove_';

    const REMOVE_FLAG_CLASS = 'fom-removed';

    /**
     * @var \Illuminate\Database\Eloquent\Relations\HasMany|string
     */
    protected $relation;

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
        $this->relation = $relation;

        $this->fields = new Collection();
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
        return $this->relation;
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
     * Format form element name.
     *
     * @param string $column
     * @param string $key
     *
     * @return string
     */
    protected function formatElementName( $column, $key = null)
    {
        $key = is_null($key) ? static::DEFAULT_KEY_NAME : $key;

//        return sprintf('%s[%s][%s][%s]', $this->relation, $type, $key, $column);
        return sprintf('%s[%s][%s]', $this->relation, $key, $column);
    }

    protected function formatColumnName( $column, $key = null)
    {
        $key = is_null($key) ? static::DEFAULT_KEY_NAME : $key;

        return sprintf('%s.%s.%s', $this->relation, $key, $column);
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
     * Set form element name.
     *
     * @param null   $key
     *
     * @return $this
     */
    protected function setColumnName( $key = null)
    {
        $this->fields->each(function (Field $field) use ( $key) {
            $column = $field->column();

            if (is_array($column)) {
                $name = array_map(function ($col) use ( $key) {
                    return $this->formatColumnName( $col, $key);
                }, $column);
            } else {
                $name = $this->formatColumnName( $column, $key);
            }

            $field->setColumnName($name);
        });

        return $this;
    }

    /**
     * Set error key for each field in the nested form.
     *
     * @param string $parent
     * @param string $key
     *
     * @return $this
     */
    public function setErrorKey($parent,  $key)
    {
        foreach ($this->fields as $field) {
            $column = $field->column();

            $errorKey = '';

            if (is_array($column)) {
                foreach ($column as $k => $name) {
                    $errorKey[$k] = "$parent.$key.$name";
                }
            } else {
                $errorKey = "$parent.$key.{$field->column()}";
            }

            $field->setErrorKey($errorKey);
        }

        return $this;
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
            $html .= $field->render();

            if ($script = $field->getScript()) {
                $this->scripts[] = $field->getScript();

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
        return implode("\r\n", $this->scripts);
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
