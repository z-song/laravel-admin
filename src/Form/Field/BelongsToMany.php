<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;

class BelongsToMany extends MultipleSelect
{
    use BelongsToRelation;

    protected function getOptions()
    {
        if ($this->value()) {
            return array_combine($this->value(), $this->value());
        }

        return [];
    }
}
