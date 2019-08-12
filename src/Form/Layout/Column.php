<?php
/**
 * Copyright (c) 2019. Mallto.Co.Ltd.<mall-to.com> All rights reserved.
 */

namespace Encore\Admin\Form\Layout;

use Encore\Admin\Form\Field;
use Illuminate\Support\Collection;

class Column
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var int
     */
    protected $width;

    /**
     * Column constructor.
     *
     * @param int $width
     */
    public function __construct($width = 12)
    {
        $this->width = $width;
        $this->fields = new Collection();
    }

    /**
     * Add a filter to this column.
     *
     * @param Field $field
     */
    public function add(Field $field)
    {
        $this->fields->push($field);
    }

    /**
     * Get all filters in this column.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Set column width.
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get column width.
     *
     * @return int
     */
    public function width()
    {
        return $this->width;
    }


}
