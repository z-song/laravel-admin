<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\Arr;

/**
 * @property Form $form
 */
trait CanCascadeFields
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @param $operator
     * @param $value
     * @param $closure
     *
     * @return $this
     */
    public function when($operator, $value, $closure = null)
    {
        if (func_num_args() == 2) {
            $closure = $value;
            $value = $operator;
            $operator = '=';
        }

        $this->formatValues($operator, $value);

        $this->addDependents($operator, $value, $closure);

        return $this;
    }

    /**
     * @param string $operator
     * @param mixed  $value
     */
    protected function formatValues(string $operator, &$value)
    {
        if (in_array($operator, ['in', 'notIn'])) {
            $value = Arr::wrap($value);
        }

        if (is_array($value)) {
            $value = array_map('strval', $value);
        } else {
            $value = strval($value);
        }
    }

    /**
     * @param string   $operator
     * @param mixed    $value
     * @param \Closure $closure
     */
    protected function addDependents(string $operator, $value, \Closure $closure)
    {
        $this->conditions[] = compact('operator', 'value', 'closure');

        $this->form->cascadeGroup($closure, [
            'column' => $this->column(),
            'index'  => count($this->conditions) - 1,
            'class'  => $this->getCascadeClass($value),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function fill($data)
    {
        parent::fill($data);

        $this->applyCascadeConditions();
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function getCascadeClass($value)
    {
        if (is_array($value)) {
            $value = implode('-', $value);
        }

        return sprintf('cascade-%s-%s', $this->getElementClassString(), $value);
    }

    /**
     * Apply conditions to dependents fields.
     *
     * @return void
     */
    protected function applyCascadeConditions()
    {
        if ($this->form) {
            $this->form->fields()
                ->filter(function (Form\Field $field) {
                    return $field instanceof CascadeGroup
                        && $field->dependsOn($this)
                        && $this->hitsCondition($field);
                })->each->visiable();
        }
    }

    /**
     * @param CascadeGroup $group
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function hitsCondition(CascadeGroup $group)
    {
        $condition = $this->conditions[$group->index()];

        extract($condition);

        $old = old($this->column(), $this->value());

        switch ($operator) {
            case '=':
                return $old == $value;
            case '>':
                return $old > $value;
            case '<':
                return $old < $value;
            case '>=':
                return $old >= $value;
            case '<=':
                return $old <= $value;
            case '!=':
                return $old != $value;
            case 'in':
                return in_array($old, $value);
            case 'notIn':
                return !in_array($old, $value);
            case 'has':
                return in_array($value, $old);
            default:
                throw new \Exception("Operator [$operator] not support.");
        }
    }

    /**
     * Add cascade scripts to contents.
     *
     * @return void
     */
    protected function addCascadeScript()
    {
        if (empty($this->conditions)) {
            return;
        }

        $cascadeGroups = collect($this->conditions)->map(function ($condition) {
            return [
                'class'    => str_replace(' ', '.', $this->getCascadeClass($condition['value'])),
                'operator' => $condition['operator'],
                'value'    => $condition['value'],
            ];
        });

        Admin::view('admin::form.cascade', [
            'event'         => $this->cascadeEvent,
            'cascadeGroups' => $cascadeGroups,
            'selector'      => $this->getElementClassSelector(),
            'value'         => $this->getFormFrontValue(),
        ]);
    }

    /**
     * @return string
     */
    protected function getFormFrontValue()
    {
        switch (true) {
            case $this instanceof Checkbox:
                return <<<SCRIPT
var checked = $('{$this->getElementClassSelector()}:checked').map(function(){
  return $(this).val();
}).get();
SCRIPT;
            case $this instanceof SwitchField:
                return <<<'SCRIPT'
var checked = this.checked ? $(this).data('onval') : $(this).data('offval');
SCRIPT;
            case $this instanceof Radio:
            case $this instanceof Select:
            case $this instanceof MultipleSelect:
            case $this instanceof Text:
            case $this instanceof Textarea:
                return 'var checked = $(this).val();';
            default:
                throw new \InvalidArgumentException('Invalid form field type');
        }
    }
}
