<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class DateRange extends Field
{

    const COLUMN_PREFIX = 'la_daterange_';

    protected static $css = [
        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/moment/min/moment-with-locales.min.js',
        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    ];

    protected $format = 'YYYY-MM-DD';

    /**
     * Date range set
     *
     * @var \Encore\Admin\Form\Field\Date
     */
    protected $range = [];

    /**
     * Date range value
     *
     * @var array
     */
    protected $value = [];


    /**
     * DateRange2 constructor.
     *
     * @param $column
     * @param array $arguments
     */
    public function __construct($column, $arguments)
    {
        $this->column = static::COLUMN_PREFIX . $column . '_' . $arguments[0];

        $this->range['start'] = new Date($column);
        $this->range['end']   = new Date($arguments[0]);

        array_shift($arguments);
        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);

        $this->options(['format' => $this->format]);
    }


    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        $this->value = array_get($value, $this->column);

        foreach($this->range as $range){
            $range->prepare($this->value);
        }

        return $value;
    }

    public function render()
    {
        $this->options['locale'] = config('app.locale');

        $startOptions = json_encode($this->options);
        $endOptions = json_encode($this->options + ['useCurrent' => false]);

        $this->range['start']->addElementClass($this->getElementClass());
        $this->range['end']->addElementClass($this->getElementClass());

        $this->script = <<<EOT
            $('{$this->range['start']->getElementClassSelector()}').datetimepicker($startOptions);
            $('{$this->range['end']->getElementClassSelector()}').datetimepicker($endOptions);
            $("{$this->range['start']->getElementClassSelector()}").on("dp.change", function (e) {
                $('{$this->range['end']->getElementClassSelector()}').data("DateTimePicker").minDate(e.date);
            });
            $("{$this->range['end']->getElementClassSelector()}").on("dp.change", function (e) {
                $('{$this->range['start']->getElementClassSelector()}').data("DateTimePicker").maxDate(e.date);
            });
EOT;

        return parent::render()->with([
            'startVars'    => $this->range['start']->variables(),
            'endVars'      => $this->range['end']->variables()
        ]);
    }
}
