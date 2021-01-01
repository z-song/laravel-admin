<?php

namespace Encore\Admin\Form\Field;

class Url extends Text
{
    protected $rules = 'nullable|url';

    public function render()
    {
        $this->prependText('<i class="fab fa-internet-explorer fa-fw"></i>')
            ->defaultAttribute('type', 'url');

        return parent::render();
    }
}
