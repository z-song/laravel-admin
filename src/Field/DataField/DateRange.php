<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class DateRange extends DataField
{
    protected static $css = [
        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    ];

    protected static $js = [
        '/packages/admin/moment/min/moment-with-locales.min.js',
        '/packages/admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    ];

    protected $format = 'YYYY-MM-DD';

    /**
     * Column name.
     *
     * @var string
     */
    protected $column = [];

    public function __construct($column, $arguments)
    {
        $this->column['start'] = $column;
        $this->column['end'] = $arguments[0];

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

        return $value;
    }

    public function render()
    {
        $this->options['locale'] = config('app.locale');

        $startOptions = json_encode($this->options);
        $endOptions = json_encode($this->options + ['useCurrent' => false]);

        $class = $this->getElementClass();

        $this->script = <<<EOT
            $('.{$class['start']}').datetimepicker($startOptions);
            $('.{$class['end']}').datetimepicker($endOptions);
            $(".{$class['start']}").on("dp.change", function (e) {
                $('.{$class['end']}').data("DateTimePicker").minDate(e.date);
            });
            $(".{$class['end']}").on("dp.change", function (e) {
                $('.{$class['start']}').data("DateTimePicker").maxDate(e.date);
            });
EOT;

        return parent::render();
    }
}
