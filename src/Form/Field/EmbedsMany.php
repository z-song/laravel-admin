<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Jenssegers\Mongodb\Relations\EmbedsMany as Relation;

/**
 * Class EmbedsMany.
 */
class EmbedsMany extends Field
{
    protected $relationName = null;

    protected $builder = null;

    public function __construct($relation, $arguments = [])
    {
        $this->relationName = $relation;

        $this->builder = $arguments[1];

        parent::__construct($relation, $arguments);
    }

    public function render()
    {
        if ($this->form->builder()->isMode('create')) {
            return;
        }

        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('embedsMany field must be a EmbedsMany relation.');
        }

        $form = new Form($relation->getRelated(), $this->builder);

        $form->build();

        return parent::render()->with(['form' => $form->builder()])->render();
    }
}
