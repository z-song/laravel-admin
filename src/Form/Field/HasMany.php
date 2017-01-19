<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\Field;
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

        foreach ($rules as $column => $rule) {
            foreach (array_keys($input[$this->column]) as $key) {
                $newRules["{$this->column}.$key.$column"] = $rule;
            }
        }

        return Validator::make($input, $newRules, [], $attributes);
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
     * @param array $column   $column is the column name array set
     *
     * @return void.
     */
    protected function resetInputKey(array &$input, array $column)
    {
	    /**
	     * flip the column name array set
	     *
	     * for example, for the DateRange, the column like as below
	     *
	     * ["start" => "$startDate", "end" => "$endDate"]
	     *
	     * to:
	     *
	     * [ "$startDate" => "start", "$endDate" => "end" ]
	     *
	     */
        $column = array_flip($column);

	    /**
	     * $this->column is the inputs array's node name, default is the relation name
	     *
	     * So... $input[$this->column] is the data of this column's inputs data
	     *
	     * in the HasMany relation, has many data/field set, $set is field set in the below
	     */
        foreach ($input[$this->column] as $index => $set) {

	        /**
	         * foreach the field set to find the corresponding $column
	         */
	        foreach ($set as $name => $value) {
		        if (!array_key_exists($name, $column)) {
			        continue;
		        }

		        /**
		         * $newKey = 
		         */
		        $newKey = $name.$column[$name];

		        array_set($input, "{$this->column}.$index.$newKey", $value);
		        array_forget($input, "{$this->column}.$index.$name");
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

	    $form->hidden($this->getKeyName());

        $form->hidden(NestedForm::REMOVE_FLAG_NAME)->default(0)->attribute(['class' => NestedForm::REMOVE_FLAG_CLASS]);

//	    $form->setElementName($key)->setErrorKey($key);

        return $form;
    }

	/**
	 * get the HasMany relation key name
	 *
	 * @return string
	 */
	protected function getKeyName()
	{
		return $this->form->model()->{$this->relationName}()->getRelated()->getKeyName();
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
        $model = $this->form->model();

        $relation = call_user_func([$model, $this->relationName]);

        if (!$relation instanceof Relation) {
            throw new \Exception('hasMany field must be a HasMany relation.');
        }

        $forms = [];

        if ($values = $this->getDataInFlash()) {
            foreach ($values as $key => $data) {
                if ($data[NestedForm::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
	                ->fill($data)
	                ->setElementName($key)
	                ->setErrorKey($key);
            }
        } else {
            foreach ($this->value as $data) {
                $key = array_get($data, $relation->getRelated()->getKeyName());

                $forms[$key] = $this->buildNestedForm($this->column, $this->builder)
	                ->fill($data)
	                ->setElementName($key)
	                ->setErrorKey($key);
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
