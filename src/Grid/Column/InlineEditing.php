<?php

namespace Encore\Admin\Grid\Column;

use Encore\Admin\Grid\Column;
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

    public function year()
    {
        return $this->datetime('YYYY');
    }

    public function month()
    {
        return $this->datetime('MM');
    }

    public function day()
    {
        return $this->datetime('DD');
    }

    public function input($mask = [])
    {
        return $this->displayUsing(Displayers\Input::class, [$mask]);
    }

    public function ip()
    {
        return $this->input(['alias' => 'ip']);
    }

    public function email()
    {
        return $this->input(['alias' => 'email']);
    }
}
