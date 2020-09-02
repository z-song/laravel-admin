<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

/**
 * Class SwitchField.
 *
 * @author songzou<zosong@126.com>
 *
 * @see https://gitbrent.github.io/bootstrap4-toggle/
 */
class SwitchField extends Field
{
    use CanCascadeFields;

    /**
     * @var string
     */
    protected $size = 'sm';

    /**
     * @var array
     */
    protected $state = [
        'on'  => ['value' => 1, 'text' => 'ON', 'style' => ''],
        'off' => ['value' => 0, 'text' => 'OFF', 'style' => 'default'],
    ];

    /**
     * @param int    $value
     * @param string $text
     * @param string $style
     *
     * @return $this
     */
    public function on($value = 1, $text = '', $style = '')
    {
        $this->state['on'] = [
            'value' => $value,
            'text'  => $text ?: $this->state['on']['text'],
            'style' => $style ?: admin_color(),
        ];

        return $this;
    }

    /**
     * @param int    $value
     * @param string $text
     * @param string $style
     *
     * @return $this
     */
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
     *
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
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

        $this->state['on']['style'] = $this->state['on']['style'] ?: admin_color();

        $this->attribute([
            'data-onstyle'  => $this->state['on']['style'],
            'data-offstyle' => $this->state['off']['style'],
            'data-on'       => $this->state['on']['text'],
            'data-off'      => $this->state['off']['text'],
            'data-onval'    => $this->state['on']['value'],
            'data-offval'   => $this->state['off']['value'],
            'data-size'     => $this->size,
            'data-width'    => 80,
        ]);

        return parent::render();
    }
}
