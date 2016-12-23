<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class HasMany.
 */
class HasMany extends Field
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
            list($this->label, $this->builder)  = $arguments;
        }
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|Validator
     */
    public function getValidator(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = array_only($input, $this->column);

        $form = $this->buildNestedForm($this->column, $this->builder);

        $rules = $attributes = [];

        foreach ($form->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();

            if (is_array($column)) {
                foreach ($column as $key => $name) {
                    $rules[$name.$key] = $fieldRules;
                }

                $this->resetInputKey($input, $column);
            } else {
                $rules[$column] = $fieldRules;
            }

            $attributes = array_merge(
                $attributes,
                $this->formatValidationAttribute($input, $field->label(), $column)
            );
        }

        array_forget($rules, NestedForm::REMOVE_FLAG_NAME);

        if (empty($rules)) {
            return false;
        }

        $newRules = [];

        foreach ($rules as $key => $rule) {
            foreach (array_keys($input[$this->column]) as $type) {
                $newRules["{$this->column}.$type.*.$key"] = $rule;
            }
        }

        return Validator::make($input, $newRules, [], $attributes);
    }

    /**
     * Format validation attributes.
     *
     * @param array $input
     * @param string $label
     * @param string $column
     *
     * @return array
     */
    protected function formatValidationAttribute($input, $label, $column)
    {
        $new = $attributes = [];

        if (is_array($column)) {
            foreach ($column as $index => $col) {
                $new[$col.$index] = $col;
            }
        }

        foreach (array_keys(array_dot($input)) as $key) {

            if (is_string($column)) {

                if (Str::endsWith($key, ".$column")) {
                    $attributes[$key] = $label;
                }

            } else {
                foreach ($new as $k => $val) {
                    if (Str::endsWith($key, ".$k")) {
                        $attributes[$key] = $label."[$val]";
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * Reset input key for validation.
     *
     * @param array $input
     * @param array $column
     *
     * @return void.
     */
    protected function resetInputKey(array &$input, array $column)
    {
        $column = array_flip($column);

        foreach ($input[$this->column] as $type => $group) {
            foreach ($group as $key => $value) {
                foreach ($value as $k => $v) {
                    if (!array_key_exists($k, $column)) {
                        continue;
                    }

                    $newKey = $k.$column[$k];

                    array_set($input, "{$this->column}.$type.$key.$newKey", $v);
                    array_forget($input, "{$this->column}.$type.$key.$k");
                }
            }
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
        $relatedKeyName = $this->form->model()->{$this->relationName}()->getRelated()->getKeyName();

        $form = $this->buildNestedForm($this->column, $this->builder);

        return $form->setOriginal($this->original, $relatedKeyName)->prepare($input);
    }

    /**
     * Build a Nested form.
     *
     * @param string $column
     * @param \Closure$builder
     *
     * @return NestedForm
     */
    protected function buildNestedForm($column, \Closure $builder)
    {
        $form = new Form\NestedForm($column);

        call_user_func($builder, $form);

        $form->hidden(NestedForm::REMOVE_FLAG_NAME)->default(0)->attribute(['class' => NestedForm::REMOVE_FLAG_CLASS]);

        return $form;
    }

    /**
     * Get form data flashed in session.
     *
     * @param string $type
     *
     * @return mixed
     */
    protected function getDataInFlash($type)
    {
        return old($this->column.'.'.$type);
    }

    /**
     * build Nested form for related data.
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function buildRelatedForms()
    {
        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $forms = [];

        if ($old = $this->getDataInFlash(NestedForm::UPDATE_KEY_NAME_OLD)) {
            foreach ($old as $key => $data) {
                if ($data[NestedForm::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
                    ->fill($data)
                    ->setElementNameForOriginal($key)
                    ->setErrorKey($this->column, NestedForm::UPDATE_KEY_NAME_OLD, $key);
            }
        } else {
            foreach ($this->value as $data) {

                $key = array_get($data, $relation->getRelated()->getKeyName());

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
                    ->fill($data)
                    ->setElementNameForOriginal($key);
            }
        }

        $forms = $this->appendFromSession($forms);

        return $forms;
    }

    /**
     * Build a nested form use data flashed to session, then append to forms.
     *
     * @param array $forms
     *
     * @return array
     */
    protected function appendFromSession($forms)
    {
        if ($new = $this->getDataInFlash(NestedForm::UPDATE_KEY_NAME_NEW)) {
            foreach ($new as $key => $data) {

                if ($data[NestedForm::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
                    ->fill($data)
                    ->setElementNameForNew($key)
                    ->setErrorKey($this->column, NestedForm::UPDATE_KEY_NAME_NEW, $key);
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
        $template->setElementNameForNew();

        $templateHtml = $template->getFormHtml();
        $templateScript = $template->getFormScript();

        $removeClass = NestedForm::REMOVE_FLAG_CLASS;
        $defaultKey = NestedForm::DEFAULT_KEY_NAME;

        $script = <<<EOT

$('.has-many-{$this->column}').on('click', '.add', function () {

    var tpl = $('template.{$this->column}-tpl');

    var count = $('.has-many-{$this->column}-forms .has-many-{$this->column}-form').size() + 1;

    var template = tpl.html().replace(/\[{$defaultKey}\]/g, '['+count+']');
    $('.has-many-{$this->column}-forms').append(template);
    {$templateScript}
});

$('.has-many-{$this->column}-forms').on('click', '.remove', function () {
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
     * @return $this
     *
     * @throws \Exception
     */
    public function render()
    {
        return parent::render()->with([
            'forms'     => $this->buildRelatedForms(),
            'template'  => $this->buildTemplateForm(),
        ]);
    }
}
