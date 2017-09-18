<?php

namespace Encore\Admin\Grid\Filter\Field;

class Text
{
    protected $placeholder = '';

    public function variables()
    {
        return [
            'placeholder' => $this->placeholder,
        ];
    }

    public function name()
    {
        return 'text';
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }
}
