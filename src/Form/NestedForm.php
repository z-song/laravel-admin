<?php

namespace Encore\Admin\Form;

use Encore\Admin\Form;
use Illuminate\Support\Collection;

class NestedForm
{
    protected $relation;

    protected $fields;

    public function __construct($relation)
    {
        $this->relation = $relation;

        $this->fields = new Collection();
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

    public function fields()
    {
        return $this->fields;
    }

    public function __call($method, $arguments)
    {
        if ($className = Form::findFieldClass($method)) {
            $column = array_get($arguments, 0, '');

            $element = new $className($column, array_slice($arguments, 1));

            $this->pushField($element);

            return $element;
        }
    }

    public function fill($data)
    {
        $this->fields->each(function ($field) use ($data) {
            $field->fill($data);
            $column = $field->column();
            $field->setName("{$this->relation}[original][{$data['id']}][$column]");
        });

        return $this;
    }
}
