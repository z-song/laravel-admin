<?php

namespace Encore\Admin\Widgets\Navbar;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class SelectThemeButton implements Renderable
{
    public function render()
    {
        return Admin::component('admin::components.select-theme-btn');
    }
}
