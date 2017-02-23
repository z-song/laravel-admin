<?php

namespace Encore\Admin\Form\Field;

class Color extends Text
{
    protected static $css = [
        '/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js',
    ];

    /**
     * Use `hex` format.
     *
     * @return $this
     */
    public function hex()
    {
        return $this->options(['format' => 'hex']);
    }

    /**
     * Use `rgb` format.
     *
     * @return $this
     */
    public function rgb()
    {
        return $this->options(['format' => 'rgb']);
    }

    /**
     * Use `rgba` format.
     *
     * @return $this
     */
    public function rgba()
    {
        return $this->options(['format' => 'rgba']);
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $options = json_encode($this->options);

        $this->script = "$('{$this->getElementClassSelector()}').parent().colorpicker($options);";

        $this->prepend('<i></i>')
            ->defaultAttribute('style', 'width: 140px');

        return parent::render();
    }
}
