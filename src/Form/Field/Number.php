<?php

namespace Encore\Admin\Form\Field;

class Number extends Text
{
    /**
     * @var string
     */
    protected $view = 'admin::form.number';

    /**
     * Set min value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function min($value)
    {
        return $this->attribute('min', $value);
    }

    /**
     * Set max value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function max($value)
    {
        return $this->attribute('max', $value);
    }

    /**
     * Set step value of number field.
     *
     * @param int $step
     *
     * @return $this
     */
    public function step($step = 1)
    {
        return $this->attribute('step', $step);
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function prefix($prefix)
    {
        return $this->attribute('prefix', $prefix);
    }

    /**
     * @param string $suffix
     *
     * @return $this
     */
    public function suffix($suffix)
    {
        return $this->attribute('suffix', $suffix);
    }

    /**
     * @param int $decimals
     *
     * @return $this
     */
    public function decimals($decimals)
    {
        return $this->attribute('data-decimals', $decimals);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->attribute('type', 'number');

        return parent::render();
    }
}
