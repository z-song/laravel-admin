<?php

namespace Encore\Admin\Table\Displayers;

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
        return Admin::view('admin::table.display.copyable', [
            'value'    => $this->getValue(),
            'original' => $this->getOriginalValue(),
        ]);
    }
}
