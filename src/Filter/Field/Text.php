<?php

namespace Encore\Admin\Filter\Field;

class Text
{
    public function variables()
    {
        return [];
    }

    public function name()
    {
        return 'text';
    }

    public function __toString()
    {
        return view('admin::filter.text');
    }
}