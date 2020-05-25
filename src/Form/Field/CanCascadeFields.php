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
     * @return $this
     */
    public function when($operator, $value, $closure = null)
    {
        if (func_num_args() == 2) {
            $closure  = $value;
            $value    = $operator;
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
     * @param string $operator
     * @param mixed $value
     * @param \Closure $closure
     */
    protected function addDependents(string $operator, $value, \Closure $closure)
    {
        $this->conditions[] = compact('operator', 'value', 'closure');

        $dependency = [
            'field' => $this->column(),
            'group' => count($this->conditions) - 1,
        ];

        $this->form->callWithDependency($dependency, $closure);
    }

    /**
     * {@inheritDoc}
     */
    public function fill($data)
    {
        parent::fill($data);

        $this->applyCascadeConditions();
    }

    /**
     * Apply conditions to dependents fields.
     *
     * @return void
     */
    protected function applyCascadeConditions()
    {
        $this->form->fields()->filter(function (Form\Field $field) {
            return $field->isDependsOn($this);
        })->each(function (Form\Field $field) {
            $group = Arr::get($field->getDependency(), 'group');
            $field->setGroupClass(
                $this->getDependentsElementClass($group)
            );
        });
    }

    /**
     * @param int $group
     * @return array
     * @throws \Exception
     */
    protected function getDependentsElementClass(int $group)
    {
        $condition = $this->conditions[$group];

        return [
            'cascade',
            $this->hitsCondition($condition) ? '' : 'hide',
            $this->getCascadeClass($condition['value'])
        ];
    }

    /**
     * @param mixed $value
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
     * @param $operator
     * @param $value
     * @return bool
     * @throws \Exception
     */
    protected function hitsCondition($condition)
    {
        extract($condition);

        $old = old($this->column(), $this->value());

        switch ($operator) {
            case '=' :
                return $old == $value;
            case '>' :
                return $old > $value;
            case '<' :
                return $old < $value;
            case '>=' :
                return $old >= $value;
            case '<=' :
                return $old <= $value;
            case '!=' :
                return $old != $value;
            case 'in' :
                return in_array($old, $value);
            case 'notIn' :
                return !in_array($old, $value);
            case 'has' :
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

        $group = [];

        foreach ($this->conditions as $item) {
            $group[] = [
                'class'    => $this->getCascadeClass($item['value']),
                'operator' => $item['operator'],
                'value'    => $item['value']
            ];
        }

        $cascadeGroups = json_encode($group);

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
            var group = $('div.form-group.'+event.class);
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
                return "var checked = $(this).val();";
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
