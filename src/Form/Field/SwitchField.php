<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

/**
 * Class SwitchField
 *
 * @author songzou<zosong@126.com>
 *
 * @see https://gitbrent.github.io/bootstrap4-toggle/
 */
class SwitchField extends Field
{
    use CanCascadeFields;

    protected $cascadeEvent = 'change';

    protected $size;

    protected $state = [
        'on'  => ['value'  => 1, 'text' => 'ON'],
        'off' => ['value'  => 0, 'text' => 'OFF']
    ];

    public function on($value = 1, $text = '', $style = '')
    {
        $this->state['on'] = [
            'value' => $value,
            'text'  => $text ?: $this->state['on']['text'],
            'style' => $style ?: admin_theme(),
        ];

        return $this;
    }

    public function off($value = 0, $text = '', $style = '')
    {
        $this->state['off'] = [
            'value' => $value,
            'text'  => $text ?: $this->state['on']['text'],
            'style' => $style ?: 'light',
        ];

        return $this;
    }

    /**
     * @param string $size lg, sm, xs
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        $this->addCascadeScript();

        $this->addVariables([
            'state' => $this->state,
            'size'  => $this->size,
        ]);

        $this->attribute([
            'data-onstyle'  => $this->state['on']['style'],
            'data-offstyle' => $this->state['off']['style'],
            'data-on'       => $this->state['on']['text'],
            'data-off'      => $this->state['off']['text'],
            'data-size'     => $this->size,
            'data-width'    => 80,
        ]);

        return parent::render();
    }
}
