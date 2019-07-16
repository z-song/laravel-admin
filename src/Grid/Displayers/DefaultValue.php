<?php
namespace Encore\Admin\Grid\Displayers;
class DefaultValue extends AbstractDisplayer
{
    public function display($default = '')
    {
        return (isset($this->value) && !is_null($this->value) && $this->value !== '') ? $this->value : $default;
    }
}
