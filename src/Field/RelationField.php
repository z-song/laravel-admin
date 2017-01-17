<?php

namespace Encore\Admin\Field;

use Encore\Admin\Field;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;

/**
 * Class Relation Field.
 */
class RelationField extends Field
{
    /**
     * Relation value.
     *
     * @var mixed
     */
    protected $value = [];

    /**
     * Relation name.
     *
     * @var string
     */
    protected $relationName = '';

    /**
     * Form builder.
     *
     * @var \Closure
     */
    protected $builder = null;

    /**
     * HasMany groups.
     *
     * @var mixed
     */
    protected $groups = [];

    /**
     * Group template.
     *
     * @var \Encore\Admin\Field\Group
     */
    protected $template;

    /**
     * Create a new HasMany field instance.
     *
     * @param $relation
     * @param array $arguments
     */
    public function __construct($relation, $arguments = [])
    {
        $this->relationName = $relation;

        $this->column = $relation;

        if (count($arguments) == 1) {
            $this->label = $this->formatLabel();
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            list($this->label, $this->builder) = $arguments;
        }
    }

    /**
     * Render the `HasMany` field.
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function render()
    {
        return parent::render();
    }
}
