<?php

namespace Encore\Admin\Form\Field;

class Number extends Text
{
    protected $number2persian = false;
    protected $number2commaSeparated = false;
    protected $number2persianSuffix = '';
    protected $number2commaSeparatedSuffix = '';
    protected $number2persianRial2Toman = false;

    protected static $js = [
        '/vendor/laravel-admin/num2persian/dist/num2persian.min.js',
    ];

    public function render()
    {
        $this->addVariables([
            'number2persian' => $this->number2persian,
            'number2commaSeparated' => $this->number2commaSeparated,
        ]);

        $this->type = 'number';

        $this->default($this->default);

        if ($this->number2persian || $this->number2commaSeparated) {
            $this->addJs();
        }

        return parent::render();
    }

    public function enableShowAsPersian($enable = true, $suffix = '', $rial2Toman = false)
    {
        $this->number2persian = $enable;
        $this->number2persianSuffix = $suffix;
        $this->number2persianRial2Toman = $rial2Toman;

        return $this;
    }

    public function enableShowAsCommaSeparated($enable = true, $suffix = '')
    {
        $this->number2commaSeparated = $enable;
        $this->number2commaSeparatedSuffix = $suffix;

        return $this;
    }

    /**
     * Set min value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function min($value)
    {
        $this->attribute('min', $value);

        return $this;
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
        $this->attribute('max', $value);

        return $this;
    }

    protected function addJs()
    {
        $number2persianScript = '';
        if ($this->number2persian) {
            $value = $this->number2persianRial2Toman ? "(this.value / 10)" : "this.value";
            $number2persianScript = <<<SCR
            $('#number2persian-box-{$this->id}').text(parseInt($value).num2persian() + '{$this->number2persianSuffix}');
SCR;
        }

        $number2commaSeparated = '';
        if ($this->number2commaSeparated) {
            $number2commaSeparated = <<<SCR
            $('#number2comma-separated-box-{$this->id}').text(parseInt(this.value || 0).toLocaleString() + '{$this->number2commaSeparatedSuffix}');
SCR;
        }

        $this->script = <<<JSP
        $('{$this->getElementClassSelector()}').on('keyup', function() {
            $number2persianScript
            $number2commaSeparated
        });

        $(document).on('pjax:start', function(e) {
            $('{$this->getElementClassSelector()}').unbind('keyup');
        });
JSP;
    }
}
