<?php

namespace Encore\Admin\Table\Actions;

use Encore\Admin\Actions\RowAction;

class Edit extends RowAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('admin.edit');
    }

    public function getElementClass()
    {
        return parent::getElementClass() . ' dropdown-item';
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }
}
