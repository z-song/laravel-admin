<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;

class Secret extends AbstractDisplayer
{
    public function display($dotCount = 6)
    {
        return Admin::view('admin::table.display.secret', [
            'value' => $this->getValue(),
            'dots'  => str_repeat('*', $dotCount),
        ]);
    }
}
