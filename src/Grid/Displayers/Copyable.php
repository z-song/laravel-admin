<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

/**
 * Class Copyable.
 *
 * @see https://codepen.io/shaikmaqsood/pen/XmydxJ
 */
class Copyable extends AbstractDisplayer
{
    public function display()
    {
        return Admin::view('admin::grid.display.copyable', [
            'value'    => $this->getValue(),
            'original' => $this->getOriginalValue(),
        ]);
    }
}
