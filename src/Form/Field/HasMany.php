<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\NestedForm;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
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
     * View Mode.
     *
     * Supports `default` and `tab` currently.
     *
     * @var string
     */
    protected $viewMode = 'default';

    /**
     * Available views for HasMany field.
     *
     * @var array
     */
    protected $views = [
        'default' => 'admin::form.hasmany',
        'tab'     => 'admin::form.hasmanytab',
        'table'   => 'admin::form.hasmanytable',
    ];

    /**
     * Options for template.
     *
     * @var array
     */
    protected $options = [
        'allowCreate' => true,
        'allowDelete' => true,
    ];

    /**
     * Distinct fields.
     *
     * @var array
     */
    protected $distinctFields = [];

    /**
     * Create a new HasMany field instance.
     *
     * @param $relationName
     * @param array $arguments
     */
    public function __construct($relationName, $arguments = [])
    {
        $this->relationName = $relationName;

        $this->column = $relationName;

        if (count($arguments) == 1) {
            $this->label = $this->formatLabel();
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            list($this->label, $this->builder) = $arguments;
        }

        admin_assets_require('initialize');
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|\Illuminate\Contracts\Validation\Validator
     */
    public function getValidator(array $input)
    {
        if (!array_key_exists($this->column, $input)) {
            return false;
        }

        $input = Arr::only($input, $this->column);

        /** unset item that contains remove flag */
        foreach ($input[$this->column] as $key => $value) {
            if ($value[NestedForm::REMOVE_FLAG_NAME]) {
                unset($input[$this->column][$key]);
            }
        }

        $form = $this->buildNestedForm($this->column, $this->builder);

        $rules = $attributes = [];

        /* @var Field $field */
        foreach ($form->fields() as $field) {
            if (!$fieldRules = $field->getRules()) {
                continue;
            }

            $column = $field->column();

            // daterange or map field etc..
            if (is_array($column)) {
                foreach ($column as $name) {
                    $rules[$name] = $fieldRules;
                }
            } else {
                $rules[$column] = $fieldRules;
            }

            $attributes = array_merge(
                $attributes,
                $this->formatValidationAttribute($input, $field->label(), $column)
            );
        }

        Arr::forget($rules, NestedForm::REMOVE_FLAG_NAME);

        if (empty($rules)) {
            return false;
        }

        $newRules = [];

        foreach ($rules as $key => $rule) {
            $newRules["{$this->column}.*.{$key}"] = $rule;
        }

        $this->appendDistinctRules($newRules);

        return \validator($input, $newRules, $this->getValidationMessages(), $attributes);
    }

    /**
     * Set distinct fields.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function distinctFields(array $fields)
    {
        $this->distinctFields = $fields;

        return $this;
    }

    /**
     * Append distinct rules.
     *
     * @param array $rules
     */
    protected function appendDistinctRules(array &$rules)
    {
        foreach ($this->distinctFields as $field) {
            $rules["{$this->column}.*.$field"] = 'distinct';
        }
    }

    /**
     * Format validation attributes.
     *
     * @param array  $input
     * @param string $label
     * @param string $column
     *
     * @return array
     */
    protected function formatValidationAttribute($input, $label, $column)
    {
        $new = $attributes = [];

        if (is_array($column)) {
            foreach ($column as $col) {
                $new[$col] = $col;
            }
        }

        foreach (array_keys(Arr::dot($input)) as $key) {
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
     * Prepare input data for insert or update.
     *
     * @param array $input
     *
     * @return array
     */
    public function prepare($input)
    {
        $form = $this->buildNestedForm($this->column, $this->builder);

        return $form->setOriginal($this->original, $this->getKeyName())->prepare($input);
    }

    /**
     * Build a Nested form.
     *
     * @param string   $column
     * @param \Closure $builder
     * @param null     $model
     *
     * @return NestedForm
     */
    protected function buildNestedForm($column, \Closure $builder, $model = null)
    {
        $form = new Form\NestedForm($column, $model);

        $form->setForm($this->form);

        call_user_func($builder, $form);

        $form->hidden($this->getKeyName());
        $form->hidden(NestedForm::REMOVE_FLAG_NAME)
            ->default(0)
            ->addElementClass(NestedForm::REMOVE_FLAG_CLASS);

        return $form;
    }

    /**
     * Get the HasMany relation key name.
     *
     * @return string
     */
    protected function getKeyName()
    {
        if (is_null($this->form)) {
            return;
        }

        return $this->form->model()->{$this->relationName}()->getRelated()->getKeyName();
    }

    /**
     * Set view mode.
     *
     * @param string $mode currently support `tab` mode.
     *
     * @return $this
     *
     * @author Edwin Hui
     */
    public function mode($mode)
    {
        $this->viewMode = $mode;

        return $this;
    }

    /**
     * Use tab mode to showing hasmany field.
     *
     * @return HasMany
     */
    public function useTab()
    {
        return $this->mode('tab');
    }

    /**
     * Use table mode to showing hasmany field.
     *
     * @return HasMany
     */
    public function useTable()
    {
        return $this->mode('table');
    }

    /**
     * Build Nested form for related data.
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function buildRelatedForms()
    {
        if (is_null($this->form)) {
            return [];
        }

        $relation = call_user_func([$this->form->model(), $this->relationName]);

        if (!$relation instanceof Relation && !$relation instanceof MorphMany) {
            throw new \Exception('This field must be a HasMany or MorphMany relation.');
        }

        $forms = [];

        foreach ($this->value as $data) {
            Arr::set(
                $forms,
                $data[$relation->getRelated()->getKeyName()],
                $this->buildNestedForm(
                    $this->column,
                    $this->builder,
                    $relation->getRelated()->replicate()->forceFill($data)
                )->fill($data)
            );
        }

        return $forms;
    }

    /**
     * Disable create button.
     *
     * @return $this
     */
    public function disableCreate()
    {
        $this->options['allowCreate'] = false;

        return $this;
    }

    /**
     * Disable delete button.
     *
     * @return $this
     */
    public function disableDelete()
    {
        $this->options['allowDelete'] = false;

        return $this;
    }

    /**
     * Render the `HasMany` field.
     *
     * @throws \Exception
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        if ($this->viewMode == 'table') {
            return $this->renderTable();
        }

        // specify a view to render.
        $this->view = $this->views[$this->viewMode];

        $template = $this->buildNestedForm($this->column, $this->builder)->getTemplate();

        return parent::fieldRender([
            'forms'        => $this->buildRelatedForms(),
            'template'     => $template,
            'relationName' => $this->relationName,
            'options'      => $this->options,
        ]);
    }

    /**
     * Render the `HasMany` field for table style.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function renderTable()
    {
        $headers = $fields = $hidden = [];

        /* @var Field $field */
        foreach ($this->buildNestedForm($this->column, $this->builder)->fields() as $field) {
            if ($field instanceof Hidden) {
                $hidden[] = $field->render();
            } else {
                /* Hide label and set field width 100% */
                if ($this->viewMode === 'default') {
                    $field->setLabelClass(['d-none']);
                    $field->setWidth(12, 0);
                }
                $fields[] = $field->render();
                $headers[] = $field->label();
            }
        }

        /* Build row elements */
        $template = array_reduce($fields, function ($all, $field) {
            return $all."<td>{$field}</td>";
        }, '');

        /* Build cell with hidden elements */
        $template .= '<td class="d-none">'.implode('', $hidden).'</td>';

        // specify a view to render.
        $this->view = $this->views[$this->viewMode];

        return parent::fieldRender([
            'headers'      => $headers,
            'forms'        => $this->buildRelatedForms(),
            'template'     => $template,
            'relationName' => $this->relationName,
            'options'      => $this->options,
        ]);
    }
}
