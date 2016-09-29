<?php

namespace Encore\Admin;

use Encore\Admin\Facades\Admin as AdminManager;

class TreeGrid
{
    public function variables()
    {
        return [];
    }

    public function render()
    {
        $script = <<<'SCRIPT'
            $('.dd').nestable({});
SCRIPT;

        AdminManager::script($script);

        return view('admin::tree-grid', $this->variables())->render();
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
