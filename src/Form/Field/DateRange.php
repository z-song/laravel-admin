<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class DateRange extends Field
{
    protected $view = 'admin::form.daterange';

    protected $icon = 'fa-calendar-alt';

    /**
     * @var array
     */
    protected $options = [
        'format'           => 'YYYY-MM-DD',
        'allowInputToggle' => true,
        'icons'            => [
            'time' => 'fas fa-clock',
        ],
    ];

    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

    /**
     * DateRange constructor.
     *
     * @param $column
     * @param $arguments
     */
    public function __construct($column, $arguments)
    {
        $this->column['start'] = $column;
        $this->column['end'] = $arguments[0];

        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);
    }

    /**
     * Set picker format.
     *
     * @param string $format
     *
     * @return $this
     */
    public function format($format)
    {
        return $this->options(compact('format'));
    }

    /**
     * Set max value.
     *
     * @param string $maxDate
     *
     * @return $this
     */
    public function max($maxDate)
    {
        return $this->options(compact('maxDate'));
    }

    /**
     * Set min value.
     *
     * @param string $minDate
     *
     * @return $this
     */
    public function min($minDate)
    {
        return $this->options(compact('minDate'));
    }

    /**
     * Set enabled values.
     *
     * @param array|string $value
     *
     * @return $this
     */
    public function enable($enabledDates)
    {
        return $this->options(compact('enabledDates'));
    }

    /**
     * Set disabled values.
     *
     * @param $value
     *
     * @return $thiss
     */
    public function disable($disabledDates = null)
    {
        return $this->options(compact('disabledDates'));
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
        $this->options(['locale' => $this->options['locale'] ?? config('app.locale')]);

        $this->addVariables([
            'icon'          => $this->icon,
            'start_options' => $this->options,
            'end_options'   => array_merge($this->options, ['useCurrent' => false]),
        ]);

        $this->attribute(['autocomplete' => 'off']);

        return parent::render();
    }
}
