<?php

namespace Encore\Admin\Field\RelationField;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm2;
use Encore\Admin\Field\RelationField;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


/**
 * Class HasMany.
 */
class HasMany2_old extends RelationField
{
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
     * Form data.
     *
     * @var array
     */
    protected $value = [];

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
     * Prepare input data for insert or update.
     *
     * @param array $input
     *
     * @return array
     */
    public function prepare($input)
    {
        $relatedKeyName = $this->owner->model()->{$this->relationName}()->getRelated()->getKeyName();

        $form = $this->buildNestedForm($this->column, $this->builder);

        return $form->setOriginal($this->original, $relatedKeyName)->prepare($input);
    }

    /**
     * Build a Nested form.
     *
     * @param string $column
     * @param \Closure$builder
     *
     * @return NestedForm2
     */
    protected function buildNestedForm($column, \Closure $builder)
    {
        $form = new Form\NestedForm2($column);

        call_user_func($builder, $form);

        $form->hidden(NestedForm2::REMOVE_FLAG_NAME)->default(0)->attribute(['class' => NestedForm2::REMOVE_FLAG_CLASS]);

        return $form;
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
     * build Nested form for related data.
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function buildRelatedForms()
    {
        $model = $this->owner->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $forms = [];

        if ($datas = $this->getDataInFlash()) {
            foreach ($datas as $key => $data) {
                if ($data[Form::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
                    ->fill($data)
//                    ->setColumnName($key)
                    ->setElementName($key)
                    ->setErrorKey($this->column, $key);
            }
        } else {
            foreach ($this->value as $data) {
                $key = array_get($data, $relation->getRelated()->getKeyName());

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
                    ->fill($data)
//                    ->setColumnName($key)
                    ->setElementName($key)
                ;
            }
        }

        return $forms;
    }

    /**
     * Build a Nested form template for dynamically add sub form .
     *
     * @return string
     */
    protected function buildTemplateForm()
    {
        $template = $this->buildNestedForm($this->column, $this->builder);
        $template->setElementName();

        $templateHtml = $template->getFormHtml();
        $templateScript = $template->getFormScript();

        $removeClass = NestedForm2::REMOVE_FLAG_CLASS;
        $defaultKey = NestedForm2::DEFAULT_KEY_NAME;

        $script = <<<EOT

$('#has-many-{$this->column}').on('click', '.add', function () {

    var tpl = $('template.{$this->column}-tpl');

    var count = $('#has-many-{$this->column}-forms .has-many-{$this->column}-form').size() + 1;

    var template = tpl.html().replace(/\[{$defaultKey}\]/g, '['+count+']');
    $('#has-many-{$this->column}-forms').append(template);
    {$templateScript}
});

$('#has-many-{$this->column}-forms').on('click', '.remove', function () {
    $(this).closest('.has-many-{$this->column}-form').hide();
    $(this).closest('.has-many-{$this->column}-form').find('.$removeClass').val(1);
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
            'forms'     => $this->buildRelatedForms(),
            'template'  => $this->buildTemplateForm(),
        ]);
    }
}
