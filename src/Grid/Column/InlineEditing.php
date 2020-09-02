<?php

namespace Encore\Admin\Grid\Column;

use Encore\Admin\Grid\Displayers;

trait InlineEditing
{
    /**
     * @param string $selectable
     *
     * @return $this
     */
    public function belongsTo($selectable)
    {
        if (method_exists($selectable, 'display')) {
            $this->display($selectable::display());
        }

        return $this->displayUsing(Displayers\BelongsTo::class, [$selectable]);
    }

    /**
     * @param string $selectable
     *
     * @return $this
     */
    public function belongsToMany($selectable)
    {
        if (method_exists($selectable, 'display')) {
            $this->display($selectable::display());
        }

        return $this->displayUsing(Displayers\BelongsToMany::class, [$selectable]);
    }

    /**
     * Upload file.
     *
     * @return $this
     */
    public function upload()
    {
        return $this->displayUsing(Displayers\Upload::class);
    }

    /**
     * Upload many files.
     *
     * @return $this
     */
    public function uplaodMany()
    {
        return $this->displayUsing(Displayers\Upload::class, [true]);
    }

    /**
     * Grid inline datetime picker.
     *
     * @param string $format
     *
     * @return $this
     */
    public function datetime($format = 'YYYY-MM-DD HH:mm:ss')
    {
        return $this->displayUsing(Displayers\Datetime::class, [$format]);
    }

    /**
     * Grid inline date picker.
     *
     * @param string $format
     *
     * @return $this
     */
    public function date()
    {
        return $this->datetime('YYYY-MM-DD');
    }

    /**
     * Grid inline time picker.
     *
     * @param string $format
     *
     * @return $this
     */
    public function time()
    {
        return $this->datetime('HH:mm:ss');
    }

    /**
     * Grid inline year picker.
     *
     * @return $this
     */
    public function year()
    {
        return $this->datetime('YYYY');
    }

    /**
     * Grid inline month picker.
     *
     * @return $this
     */
    public function month()
    {
        return $this->datetime('MM');
    }

    /**
     * Grid inline day picker.
     *
     * @return $this
     */
    public function day()
    {
        return $this->datetime('DD');
    }

    /**
     * Grid inline input.
     *
     * @return $this
     */
    protected function input($mask = [])
    {
        return $this->displayUsing(Displayers\Input::class, [$mask]);
    }

    /**
     * Grid inline text input.
     *
     * @return $this
     */
    public function text()
    {
        return $this->input();
    }

    /**
     * Grid inline ip input.
     *
     * @return $this
     */
    public function ip()
    {
        return $this->input(['alias' => 'ip']);
    }

    /**
     * Grid inline email input.
     *
     * @return $this
     */
    public function email()
    {
        return $this->input(['alias' => 'email']);
    }

    /**
     * Grid inline url input.
     *
     * @return $this
     */
    public function url()
    {
        return $this->input(['alias' => 'url']);
    }

    /**
     * Grid inline currency input.
     *
     * @return $this
     */
    public function currency()
    {
        return $this->input([
            'alias'              => 'currency',
            'radixPoint'         => '.',
            'prefix'             => '',
            'removeMaskOnSubmit' => true,
        ]);
    }

    /**
     * Grid inline decimal input.
     *
     * @return $this
     */
    public function decimal()
    {
        return $this->input([
            'alias'      => 'decimal',
            'rightAlign' => true,
        ]);
    }

    /**
     * Grid inline integer input.
     *
     * @return $this
     */
    public function integer()
    {
        return $this->input([
            'alias' => 'integer',
        ]);
    }

    /**
     * Grid inline textarea.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function textarea($rows = 5)
    {
        return $this->displayUsing(Displayers\Textarea::class, [$rows]);
    }

    /**
     * Grid inline tiemzone select.
     *
     * @return $this
     */
    public function timezone()
    {
        $identifiers = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        $options = collect($identifiers)->mapWithKeys(function ($timezone) {
            return [$timezone => $timezone];
        })->toArray();

        return $this->select($options);
    }

    /**
     * Grid inline select.
     *
     * @param array $options
     *
     * @return mixed
     */
    public function select(array $options)
    {
        return $this->displayUsing(Displayers\Select::class, [$options]);
    }

    /**
     * Grid inline multiple-select input.
     *
     * @param array $options
     *
     * @return $this
     */
    public function multipleSelect(array $options)
    {
        return $this->displayUsing(Displayers\MultipleSelect::class, [$options]);
    }

    /**
     * Grid inline checkbox.
     *
     * @param array $options
     *
     * @return $this
     */
    public function checkbox(array $options)
    {
        return $this->displayUsing(Displayers\Checkbox::class, [$options]);
    }

    /**
     * Grid inline checkbox.
     *
     * @param array $options
     *
     * @return $this
     */
    public function radio(array $options)
    {
        return $this->displayUsing(Displayers\Radio::class, [$options]);
    }

    /**
     * Grid inline switch.
     *
     * @param array $states
     *
     * @return $this
     */
    public function switch(array $states = [])
    {
        return $this->displayUsing(Displayers\SwitchDisplay::class, [$states]);
    }

    /**
     * Grid inline switch group.
     *
     * @param array $states
     *
     * @return $this
     */
    public function switchGroup(array $columns = [], array $states = [])
    {
        return $this->displayUsing(Displayers\SwitchGroup::class, [$columns, $states]);
    }
}
