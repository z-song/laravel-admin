<?php

namespace Encore\Admin\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Field;
use Encore\Admin\Field\DataField;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class HasMany.
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
     * Get form data flashed in session.
     *
     * @return mixed
     */
    protected function getDataInFlash()
    {
        return old($this->column);
    }

    /**
     * Build a Nested form.
     *
     * @param string $relationName
     * @param \Closure$builder
     *
     * @return NestedForm2
     */
    protected function buildGroup($relationName, \Closure $builder, $key)
    {
        $group = new Group($relationName, $key);

        call_user_func($builder, $group);

        $pk = $this->owner->model()->$relationName()->getRelated()->getKeyName();

        $group->hidden($pk);

        $group->hidden(Group::REMOVE_FLAG_NAME)->default(0)->attribute(['class' => Group::REMOVE_FLAG_CLASS]);

        return $group;
    }


    protected function buildGroups()
    {
        $model = $this->owner->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $groups = [];

        if ($datas = $this->getDataInFlash()) {
            foreach ($datas as $key => $data) {
                if ($data[Group::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }
                //{$this->relationName}.{$this->key}.{$column}
                $data = [$this->relationName => [ $key => $data]];

                $groups[$key] = $this->buildGroup($this->relationName, $this->builder, $key)->fill([$data]);
            }
        } else {
            foreach ($this->value as $data) {
                $key = array_get($data, $relation->getRelated()->getKeyName());

                $data = [$this->relationName => [ $key => $data]];

                $groups[$key] = $this->buildGroup($this->relationName, $this->builder, $key)->fill($data);
            }
        }

        return $groups;
    }


    /**
     * Build a Nested form template for dynamically add sub form .
     *
     * @return string
     */
    protected function buildTemplateGroup()
    {
        $template = $this->buildGroup($this->relationName, $this->builder, Group::DEFAULT_KEY_NAME);

        $templateHtml = $template->getFormHtml();
        $templateScript = $template->getFormScript();

        $removeClass = Group::REMOVE_FLAG_CLASS;
        $defaultKey = Group::DEFAULT_KEY_NAME;

        $script = <<<EOT

$('#has-many-{$this->relationName}').on('click', '.add', function () {

    var tpl = $('template.{$this->relationName}-tpl');

    var count = $('#has-many-{$this->relationName}-forms .has-many-{$this->relationName}-form').size() + 1;

    var template = tpl.html().replace(/\[{$defaultKey}\]/g, '['+count+']');
    $('#has-many-{$this->relationName}-forms').append(template);
    {$templateScript}
});

$('#has-many-{$this->relationName}-forms').on('click', '.remove', function () {
    $(this).closest('.has-many-{$this->relationName}-form').hide();
    $(this).closest('.has-many-{$this->relationName}-form').find('.$removeClass').val(1);
});

EOT;

        Admin::script($script);

        return $templateHtml;
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
        return parent::render()->with([
            'groups'     => $this->buildGroups(),
            'template'  => $this->buildTemplateGroup(),
        ]);
    }




    /**
     * Prepare input data for insert or update.
     *
     * @param array $input
     *
     * @return array
     */
//    public function prepare($input)
//    {
//        $relatedKeyName = $this->form->model()->{$this->relationName}()->getRelated()->getKeyName();
//
//        $form = $this->buildNestedForm($this->column, $this->builder);
//
//        return $form->setOriginal($this->original, $relatedKeyName)->prepare($input);
//    }










}
