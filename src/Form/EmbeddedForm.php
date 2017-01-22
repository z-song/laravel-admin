<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;

class EmbeddedForm
{
    /**
     * @var Form
     */
    protected $parent = null;

    /**
     * Fields in form.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Column name for this form.
     *
     * @var string
     */
    protected $column;

    /**
     * EmbeddedForm constructor.
     *
     * @param string $column
     */
    public function __construct($column)
    {
        $this->column = $column;

        $this->fields = new Collection();
    }

    /**
     * Get all fields in current form.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Set parent form for this form.
     *
     * @param Form $parent
     *
     * @return $this
     */
    public function setParent(Form $parent)
    {
        $this->parent = $parent;

        return $this;
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
        foreach ($this->fields as $field) {
            $field->fill($data);
        }

        return $this;
    }

    /**
     * Format form, set `element name` `error key` and `element class`.
     *
     * @param Field $field
     *
     * @return Field
     */
    protected function formatField(Field $field)
    {
        $jsonKey = $field->column();

        $elementName = $elementClass = $errorKey = '';

        if (is_array($jsonKey)) {
            foreach ($jsonKey as $index => $name) {
                $elementName[$index] = "{$this->column}[$name]";
                $errorKey[$index] = "{$this->column}.$name";
                $elementClass[$index] = "{$this->column}_$name";
            }
        } else {
            $elementName = "{$this->column}[$jsonKey]";
            $errorKey = "{$this->column}.$jsonKey";
            $elementClass = "{$this->column}_$jsonKey";
        }

        $field->setElementName($elementName)
            ->setErrorKey($errorKey)
            ->setElementClass($elementClass);

        return $field;
    }

    /**
     * Add a field to form.
     *
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $field = $this->formatField($field);

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

            $field = new $className($column, array_slice($arguments, 1));

            $field->setForm($this->parent);

            $this->pushField($field);

            return $field;
        }

        return $this;
    }
}
