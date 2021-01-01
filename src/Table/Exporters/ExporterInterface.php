<?php

namespace Encore\Admin\Table\Exporters;

interface ExporterInterface
{
    /**
     * Export data from table.
     *
     * @return mixed
     */
    public function export();
}
