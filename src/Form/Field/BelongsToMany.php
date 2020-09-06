<?php

namespace Encore\Admin\Form\Field;

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
