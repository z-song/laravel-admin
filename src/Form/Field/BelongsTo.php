<?php

namespace Encore\Admin\Form\Field;

class BelongsTo extends Select
{
    use BelongsToRelation;

    protected function getOptions()
    {
        if ($value = $this->value()) {
            return [$value => $value];
        }

        return [];
    }
}
