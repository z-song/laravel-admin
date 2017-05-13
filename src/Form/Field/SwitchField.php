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
        'null' => ['value' => null, 'text' => 'UNSET', 'color' => 'warning'],
        'on'   => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off'  => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];

    public function __construct($column, $arguments = [], $modelName = '')
    {
        $this->states['on']['text'] = admin_translate($modelName, $this->states['on']['text']);
        $this->states['off']['text'] = admin_translate($modelName, $this->states['off']['text']);
        parent::__construct($column, $arguments, $modelName);
    }

    public function states($states = [])
    {
        foreach (array_dot($states) as $key => $state) {
            array_set($this->states, $key, $state);
        }

        return $this;
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
        $fieldValue = $this->value();
        foreach ($this->states as $state => $option) {
            if ($fieldValue === $option['value']) {
                $this->value = $state;
                break;
            }
        }
        $this->setDataSet([
            'size'          => 'small',
            'onText'        => $this->states['on']['text'],
            'offText'       => $this->states['off']['text'],
            'onColor'       => $this->states['on']['color'],
            'offColor'      => $this->states['off']['color'],
            'indeterminate' => ($fieldValue === false ? true : false),
        ]);

        return parent::render();
    }
}
