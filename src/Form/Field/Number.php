<?php

namespace Encore\Admin\Form\Field;

class Number extends Text
{
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
}
