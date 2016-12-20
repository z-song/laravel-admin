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
            $this->label = $this->formatLabel();
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            $this->label = $arguments[0];
            $this->builder = $arguments[1];
        }
    }

    public function prepare($input)
    {
        $form = $this->buildNestedForm($this->column, $this->builder);

        return $form->prepare($input);
    }

    public function render()
    {
        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $relatedKeyName = $relation->getRelated()->getKeyName();

        $forms = [];

        foreach ($this->value as $data) {

            $form = $this->buildNestedForm($this->column, $this->builder);

            $pk = array_get($data, $relatedKeyName);

            $forms[$pk] = $form->fill($data)->setElementNameForOriginal($pk);
        }

        $template = $this->buildNestedForm($this->column, $this->builder);

        $templateHtml = $template->setElementNameForNew()->getFormHtml();

        $script = <<<EOT

$('.has-many-{$this->column}').on('click', '.add', function () {
    var template = $('template.{$this->column}-tpl').html();
    $('.has-many-{$this->column}-forms').append(template);
    {$template->getScript()}
});

$('.has-many-{$this->column}-forms').on('click', '.remove', function () {
    $(this).closest('.has-many-{$this->column}-form').hide();
    $(this).closest('.has-many-{$this->column}-form').find('.item-to-remove').val(1);
});

EOT;

        Admin::script($script);

        return parent::render()->with(compact('forms', 'templateHtml'));
    }

    protected function buildNestedForm($column, $builder)
    {
        $form = new Form\NestedForm($column);

        call_user_func($builder, $form);

        $form->hidden('_remove')->default(0)->attribute(['class' => 'item-to-remove']);

        return $form;
    }
}
