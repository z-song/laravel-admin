<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;

class Limit extends AbstractDisplayer
{
    public function display($limit = 100, $end = '...')
    {
        $value = Str::limit($this->value, $limit, $end);

        $original = $this->getOriginalValue();

        if ($value == $original) {
            return $value;
        }

        return Admin::view('admin::table.display.limit', compact('value', 'original'));
    }
}
