<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Rules\JalaliDate as RulesJalaliDate;
use Exception;
use Morilog\Jalali\Jalalian;

class JalaliDate extends Text
{
    protected $format = 'Y-m-d';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function render()
    {
        $this->prepend('<i class="fa fa-calendar fa-fw"></i>');

        $this->value(jdate($this->value())->format($this->format));

        return parent::render();
    }

    /**
     * Prepare data for insert or update
     *
     * @param string $value Received value
     * @return string Value to save on DB
     */
    public function prepare($value)
    {
        $tok = preg_split('/(\-|\/)/', $value, 3);

        return (new Jalalian($tok[0], $tok[1], $tok[2]))->toCarbon();
    }
}
