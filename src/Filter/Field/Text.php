<?php

namespace Encore\Admin\Filter\Field;

class Text
{
    public function variables()
    {
        return [];
    }

    public function __toString()
    {
        return 'text';
    }
}