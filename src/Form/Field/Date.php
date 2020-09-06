<?php

namespace Encore\Admin\Form\Field;

class Date extends Text
{
    /**
     * @var string
     */
    protected $view = 'admin::form.date';

    /**
     * @var string
     */
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
     * Set default value.
     *
     * @param string $value
     *
     * @return $this
     */
    public function default($defaultDate)
    {
        return $this->options(compact('defaultDate'));
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

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->options(['locale' => $this->options['locale'] ?? config('app.locale')]);

        $this->addVariables([
            'icon'    => $this->icon,
            'options' => $this->options,
        ]);

        $this->attribute(['autocomplete' => 'off']);

        return parent::render();
    }
}
