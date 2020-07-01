<?php

namespace Encore\Admin\Grid\Selectable;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class Checkbox extends AbstractDisplayer
{
    public function display($key = '')
    {
        $value = $this->getAttribute($key);

        return <<<HTML
<input type="checkbox" name="item" class="select" value="{$value}"/>
HTML;
    }
}
