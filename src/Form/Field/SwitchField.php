<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class SwitchField extends Field
{
    protected static $css = [
        '/packages/admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
    ];

    protected static $js = [
        '/packages/admin/bootstrap-switch/dist/js/bootstrap-switch.min.js',
    ];

    protected $states = [
        'on'  => ['value' => 1, 'text' => 'On'],
        'off' => ['value' => 0, 'text' => 'Off'],
    ];

    public function states($states = [])
    {
        foreach (array_dot($states) as $key => $state) {
            array_set($this->states, $key, $state);
        }
    }

    public function prepare($value)
    {
        if (isset($this->states[$value])) {
            return $this->states[$value]['value'];
        }

        return $value;
    }

    public function render()
    {
        foreach ($this->states as $state => $option) {
            if ($this->value == $option['value']) {
                $this->value = $state;
                break;
            }
        }

        $this->script = <<<EOT

$('.{$this->getElementClass()}_checkbox').bootstrapSwitch({
    size:'small',
    onSwitchChange: function(event, state) {
        $('.{$this->getElementClass()}').val(state ? 'on' : 'off');
    }
});

EOT;

        return parent::render();
    }
}
