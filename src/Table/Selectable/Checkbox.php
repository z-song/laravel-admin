<?php

namespace Encore\Admin\Table\Selectable;

use Encore\Admin\Table\Displayers\AbstractDisplayer;

class Checkbox extends AbstractDisplayer
{
    public function display($key = '')
    {
        $value = $this->getAttribute($key);

        $id = uniqid();

        $html = <<<HTML
<span class="icheck-%s">
    <input id="selectable-{$id}" type="checkbox" name="item" class="select" value="{$value}"/>
    <label for="selectable-{$id}"></label>
</span>
HTML;

        return admin_color($html);
    }
}
