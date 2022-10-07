<?php

namespace Encore\Admin\Form\Field;

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
        $this->prepend('<i class="fa-solid fa-calendar fa-fw"></i>');

        $this->value(empty($this->value()) ? null : jdate($this->value())->format($this->format));

        return parent::render();
    }

    public function getValidator(array $input)
    {
        $this->rules('digits:8');

        $value = $input[$this->column()];

        try {
            $tok = $this->tokenizeValue($value);

            $input[$this->column()] =
                str_pad($tok[0], 4, '0', STR_PAD_LEFT) .
                str_pad($tok[1], 2, '0', STR_PAD_LEFT) .
                str_pad($tok[2], 2, '0', STR_PAD_LEFT);
        } catch (Exception $e) {

            $this->removeRule('digits:8');
            $this->rules('shamsi_date');
        }

        return parent::getValidator($input);
    }

    /**
     * Prepare data for insert or update
     *
     * @param string $value Received value
     * @return string Value to save on DB
     */
    public function prepare($value)
    {
        $tok = $this->tokenizeValue($value);

        return (new Jalalian($tok[0], $tok[1], $tok[2]))->toCarbon();
    }

    protected function tokenizeValue($value)
    {
        if (is_numeric($value) && strlen($value) === 8) {

            $tok = [
                substr($value, 0, 4),
                substr($value, 4, 2),
                substr($value, 6, 2),
            ];
        } else {

            $tok = preg_split('/(\-|\/)/', $value, 3);

            if (count($tok) < 3) throw new Exception('Invalid JalaliDate!');
        }

        // Test is able to create jalalian
        new Jalalian($tok[0], $tok[1], $tok[2]);

        return $tok;
    }
}
