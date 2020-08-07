<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class SwitchField extends Field
{
    use CanCascadeFields;

    protected  $cascadeEvent = 'switchChange.bootstrapSwitch';

    protected $states = [
        1  => ['text' => 'ON', 'color' => 'primary'],
        0 => ['text' => 'OFF', 'color' => 'default'],
    ];

    protected $size = 'small';

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function states($states = [])
    {
        foreach (Arr::dot($states) as $key => $state) {
            Arr::set($this->states, $key, $state);
        }

        return $this;
    }

    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        foreach ($this->states as $state => $option) {
            if ($this->value() == $option['value']) {
                $this->value = $state;
                break;
            }
        }

        $this->addCascadeScript();

        $this->addVariables([
            'states' => $this->states,
            'size'   => $this->size,
        ]);

        return parent::render();
    }
}
