<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Textarea extends DataField
{
    /**
     * Default rows of textarea.
     *
     * @var int
     */
    protected $rows = 5;

    /**
     * Set rows of textarea.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function rows($rows = 5)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return parent::render()->with(['rows' => $this->rows]);
    }
}
