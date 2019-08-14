<?php

namespace Encore\Admin\Grid\Displayers;

class Prefix extends AbstractDisplayer
{
    public function display($prefix = null, $delimiter = '&nbsp;')
    {
        if ($prefix instanceof \Closure) {
            $prefix = $prefix->call($this->row, $this->getValue());
        }

        return <<<HTML
{$prefix}{$delimiter}{$this->getValue()}
HTML;
    }
}
