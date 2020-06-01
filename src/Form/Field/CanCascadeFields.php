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
        if( $this->form ) {
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
                'class'    => $this->getCascadeClass($condition['value']),
                'operator' => $condition['operator'],
                'value'    => $condition['value'],
            ];
        })->toJson();

        $script = <<<SCRIPT
(function () {
    var operator_table = {
        '=': function(a, b) {
            if ($.isArray(a) && $.isArray(b)) {
                return $(a).not(b).length === 0 && $(b).not(a).length === 0;
            }

            return a == b;
        },
        '>': function(a, b) { return a > b; },
        '<': function(a, b) { return a < b; },
        '>=': function(a, b) { return a >= b; },
        '<=': function(a, b) { return a <= b; },
        '!=': function(a, b) {
             if ($.isArray(a) && $.isArray(b)) {
                return !($(a).not(b).length === 0 && $(b).not(a).length === 0);
             }

             return a != b;
        },
        'in': function(a, b) { return $.inArray(a, b) != -1; },
        'notIn': function(a, b) { return $.inArray(a, b) == -1; },
        'has': function(a, b) { return $.inArray(b, a) != -1; },
    };
    var cascade_groups = {$cascadeGroups};
    $('{$this->getElementClassSelector()}').on('{$this->cascadeEvent}', function (e) {

        {$this->getFormFrontValue()}

        cascade_groups.forEach(function (event) {
            var group = $('div.cascade-group.'+event.class);
            if( operator_table[event.operator](checked, event.value) ) {
                group.removeClass('hide');
            } else {
                group.addClass('hide');
            }
        });
    })
})();
SCRIPT;

        Admin::script($script);
    }

    /**
     * @return string
     */
    protected function getFormFrontValue()
    {
        switch (get_class($this)) {
            case Radio::class:
            case RadioButton::class:
            case RadioCard::class:
            case Select::class:
            case BelongsTo::class:
            case BelongsToMany::class:
            case MultipleSelect::class:
                return 'var checked = $(this).val();';
            case Checkbox::class:
            case CheckboxButton::class:
            case CheckboxCard::class:
                return <<<SCRIPT
var checked = $('{$this->getElementClassSelector()}:checked').map(function(){
  return $(this).val();
}).get();
SCRIPT;
            default:
                throw new \InvalidArgumentException('Invalid form field type');
        }
    }
}
