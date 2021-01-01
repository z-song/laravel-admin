<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

trait ValidatesField
{
    /**
     * Validation rules.
     *
     * @var array|\Closure
     */
    protected $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array|\Closure
     */
    public $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array|\Closure
     */
    public $updateRules = [];

    /**
     * @var \Closure
     */
    protected $validator;

    /**
     * Validation messages.
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Key for errors.
     *
     * @var mixed
     */
    protected $errorKey;

    /**
     * Add `required` attribute to current field if has required rule,
     * except file and image fields.
     *
     * @param array $rules
     */
    protected function addRequiredAttribute($rules)
    {
        if (!is_array($rules)) {
            return;
        }

        if (!in_array('required', $rules, true)) {
            return;
        }

        $this->setLabelClass(['asterisk']);

        // Only text field has `required` attribute.
        if (!$this instanceof Form\Field\Text) {
            return;
        }

        //do not use required attribute with tabs
        if ($this->form && $this->form->getTab()) {
            return;
        }

        $this->required();
    }

    /**
     * If has `required` rule, add required attribute to this field.
     */
    protected function addRequiredAttributeFromRules()
    {
        if ($this->data === null) {
            // Create page
            $rules = $this->creationRules ?: $this->rules;
        } else {
            // Update page
            $rules = $this->updateRules ?: $this->rules;
        }

        $this->addRequiredAttribute($rules);
    }

    /**
     * Format validation rules.
     *
     * @param array|string $rules
     *
     * @return array
     */
    protected function formatRules($rules): array
    {
        if (is_string($rules)) {
            $rules = array_filter(explode('|', $rules));
        }

        return array_filter((array) $rules);
    }

    /**
     * @param string|array|Closure $input
     * @param string|array         $original
     *
     * @return array|Closure
     */
    protected function mergeRules($input, $original)
    {
        if ($input instanceof Closure) {
            $rules = $input;
        } else {
            if (!empty($original)) {
                $original = $this->formatRules($original);
            }

            $rules = array_merge($original, $this->formatRules($input));
        }

        return $rules;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param array|callable|string $rules
     * @param array                 $messages
     *
     * @return $this
     */
    public function rules($rules = null, $messages = []): self
    {
        $this->rules = $this->mergeRules($rules, $this->rules);

        $this->setValidationMessages('default', $messages);

        return $this;
    }

    /**
     * Set the update validation rules for the field.
     *
     * @param array|callable|string $rules
     * @param array                 $messages
     *
     * @return $this
     */
    public function updateRules($rules = null, $messages = []): self
    {
        $this->updateRules = $this->mergeRules($rules, $this->updateRules);

        $this->setValidationMessages('update', $messages);

        return $this;
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param array|callable|string $rules
     * @param array                 $messages
     *
     * @return $this
     */
    public function creationRules($rules = null, $messages = []): self
    {
        $this->creationRules = $this->mergeRules($rules, $this->creationRules);

        $this->setValidationMessages('creation', $messages);

        return $this;
    }

    /**
     * Set validation messages for column.
     *
     * @param string $key
     * @param array  $messages
     *
     * @return $this
     */
    public function setValidationMessages($key, array $messages): self
    {
        $this->validationMessages[$key] = $messages;

        return $this;
    }

    /**
     * Get validation messages for the field.
     *
     * @return array|mixed
     */
    public function getValidationMessages()
    {
        // Default validation message.
        $messages = $this->validationMessages['default'] ?? [];

        if (request()->isMethod('POST')) {
            $messages = $this->validationMessages['creation'] ?? $messages;
        } elseif (request()->isMethod('PUT')) {
            $messages = $this->validationMessages['update'] ?? $messages;
        }

        return $messages;
    }

    /**
     * Get field validation rules.
     *
     * @return string
     */
    protected function getRules()
    {
        if (request()->isMethod('POST')) {
            $rules = $this->creationRules ?: $this->rules;
        } elseif (request()->isMethod('PUT')) {
            $rules = $this->updateRules ?: $this->rules;
        } else {
            $rules = $this->rules;
        }

        if ($rules instanceof \Closure) {
            $rules = $rules->call($this, $this->form);
        }

        if (is_string($rules)) {
            $rules = array_filter(explode('|', $rules));
        }

        if (!$this->form || !$this->form instanceof Form) {
            return $rules;
        }

        if (!$id = $this->form->model()->getKey()) {
            return $rules;
        }

        if (is_array($rules)) {
            foreach ($rules as &$rule) {
                if (is_string($rule)) {
                    $rule = str_replace('{{id}}', $id, $rule);
                }
            }
        }

        return $rules;
    }

    /**
     * Remove a specific rule by keyword.
     *
     * @param string $rule
     *
     * @return void
     */
    protected function removeRule($rule)
    {
        if (is_array($this->rules)) {
            array_delete($this->rules, $rule);

            return;
        }

        if (!is_string($this->rules)) {
            return;
        }

        $pattern = "/{$rule}[^\|]?(\||$)/";
        $this->rules = preg_replace($pattern, '', $this->rules, -1);
    }

    /**
     * Set field validator.
     *
     * @param callable $validator
     *
     * @return $this
     */
    public function validator(callable $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Get key for error message.
     *
     * @return string|array
     */
    public function getErrorKey()
    {
        return $this->errorKey ?: $this->column;
    }

    /**
     * Set key for error message.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setErrorKey($key): self
    {
        $this->errorKey = $key;

        return $this;
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|\Illuminate\Contracts\Validation\Validator|mixed
     */
    public function getValidator(array $input)
    {
        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        if (is_string($this->column)) {
            if (!Arr::has($input, $this->column)) {
                return false;
            }

            $input = $this->sanitizeInput($input, $this->column);

            $rules[$this->column] = $fieldRules;
            $attributes[$this->column] = $this->label;
        }

        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                if (!array_key_exists($column, $input)) {
                    continue;
                }
                $input[$column] = Arr::get($input, $column);
                $rules[$column] = $fieldRules;
                $attributes[$column] = $this->label."[$column]";
            }
        }

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * Sanitize input data.
     *
     * @param array  $input
     * @param string $column
     *
     * @return array
     */
    protected function sanitizeInput($input, $column)
    {
        if ($this instanceof Field\MultipleSelect) {
            $value = Arr::get($input, $column);
            Arr::set($input, $column, array_filter($value));
        }

        return $input;
    }
}
