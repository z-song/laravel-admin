<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;

/**
 * Class HasMany.
 */
class HasMany extends Field
{
    protected $relationName = null;

    protected $builder = null;

    public function __construct($relation, $arguments = [])
    {
        $this->relationName = $relation;

        $this->column = $relation;

        if (count($arguments) == 1) {
            $this->label = $this->formatLabel($relation);
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            $this->label = $arguments[0];
            $this->builder = $arguments[1];
        }
    }

    public function render()
    {
        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $forms = [];

        foreach ($this->value as $data) {

            $form = new Form\NestedForm($this->column);

            call_user_func($this->builder, $form);

            $forms[] = $form->fill($data);
        }

        $template = new Form\NestedForm($this->column);

        call_user_func($this->builder, $template);

        Admin::script("$('.clone-wrapper').cloneya();");

        return parent::render()->with(compact('forms', 'template'));
    }
}
