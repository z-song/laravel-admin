<?php

namespace Encore\Admin\Table\Selectable;

use Encore\Admin\Table\Displayers\AbstractDisplayer;

class Radio extends AbstractDisplayer
{
    public function display($key = '')
    {
        $value = $this->getAttribute($key);

        return <<<HTML
<input type="radio" name="item" class="select" value="{$value}"/>
HTML;
    }
}
