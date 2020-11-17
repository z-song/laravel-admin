<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class DateRange extends Field
{
    protected static $css = [
        '/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/moment/min/moment-with-locales.min.js',
        '/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    ];

    protected $format = 'YYYY-MM-DD';
    protected $startOptions = [];
    protected $endOptions = [];
    protected $startOptionsJson;
    protected $endOptionsJson;

    /**
     * Column name.
     *
     * @var array
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
        $this->options['locale'] = config('app.locale');

    }

    public function format($format)
    {
        $this->format = $format;
        $this->options(['format' => $this->format]);
        return $this;
    }

    public function defaultStart($date)
    {
        $this->startOptions(["defaultDate" => $date]);
        return $this;
    }

    public function defaultEnd($date)
    {
        $this->endOptions(["defaultDate" => $date]);
        return $this;
    }

    public function startOptions(Array $opt = []){
        $result = array_merge($this->options, $opt);
        $this->startOptions = array_merge($this->startOptions, $result);
        $this->startOptionsJson = json_encode($this->startOptions);
        return $this;
    }


    public function endOptions(Array $opt = []){
        $result = array_merge($this->options, $opt);
        $this->endOptions = array_merge($this->endOptions, $result);
        $this->endOptionsJson = json_encode($this->endOptions);
        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {
        $this->startOptions();
        $this->endOptions();

        $class = $this->getElementClassSelector();

        $this->script = <<<EOT
            $('{$class['start']}').datetimepicker($this->startOptionsJson);
            $('{$class['end']}').datetimepicker($this->endOptionsJson);
            $("{$class['start']}").on("dp.change", function (e) {
                $('{$class['end']}').data("DateTimePicker").minDate(e.date);
            });
            $("{$class['end']}").on("dp.change", function (e) {
                $('{$class['start']}').data("DateTimePicker").maxDate(e.date);
            });
EOT;

        return parent::render();
    }
}
