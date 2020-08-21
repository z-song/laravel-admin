<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;

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
