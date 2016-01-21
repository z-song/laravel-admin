<?php

namespace Encore\Admin\Filter;

class Like extends AbstractFiled
{
    public function condition($inputs)
    {
        if(! isset($inputs[$this->column])) return;

        $this->value = $inputs[$this->column];

        return ['where' => [$this->column, 'like', "%{$this->value}%"]];
    }
}