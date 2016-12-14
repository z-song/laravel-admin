<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class SwitchField extends Field
{
    const STATE_ON = 1;
    const STATE_OFF = 0;

    protected static $css = [
        '/packages/admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
    ];

    protected static $js = [
        '/packages/admin/bootstrap-switch/dist/js/bootstrap-switch.min.js',
    ];

    protected $states = [];

    public function __construct($column, $arguments = [])
    {
        $this->initStates();

        parent::__construct($column, $arguments);
    }

    protected function initStates()
    {
        $this->states = ['on' => static::STATE_ON, 'off' => static::STATE_OFF];
    }

    public function states($states = [])
    {
        $this->states = $states;
    }

    public function prepare($value)
    {
        if (isset($this->states[$value])) {
            return $this->states[$value];
        }

        return $value;
    }

    public function render()
    {
        $key = array_search($this->value, $this->states);

        $this->value = ($key == 'on') ? 'on' : 'off';

        $this->script = <<<EOT

$('#{$this->id}_checkbox').bootstrapSwitch({
    size:'small',
    onSwitchChange: function(event, state) {
        $('#{$this->id}').val(state ? 'on' : 'off');
    }
});;

EOT;

        return parent::render();
    }
}
