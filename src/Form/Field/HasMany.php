<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Grid;
use Encore\Admin\Form\Field;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;

class HasMany extends Field
{
    protected $relationName = null;

    protected $builder = null;

    public function __construct($relation, $arguments = [])
    {
        $this->relationName = $relation;

        $this->builder = $arguments[0];

        parent::__construct($relation);
    }

    public function render()
    {
        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (! $relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $grid = new Grid($relation->getRelated(), $this->builder);

        $grid->build();

        return parent::render()->with(['grid' => $grid]);
    }
}
