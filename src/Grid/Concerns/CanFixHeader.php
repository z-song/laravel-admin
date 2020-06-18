<?php

namespace Encore\Admin\Grid\Concerns;

use Encore\Admin\Admin;

trait CanFixHeader
{
    public function fixHeader()
    {
        Admin::style(<<<STYLE
.wrapper, .grid-box .box-body {
    overflow: visible;
}

.grid-table {
    position: relative;
    border-collapse: separate;
}

.grid-table thead tr:first-child th {
    background: white;
    position: sticky;
    top: 0;
    z-index: 1;
}
STYLE
        );
    }
}
