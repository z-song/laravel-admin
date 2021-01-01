<?php

namespace Encore\Admin\Table\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Table;

class PerPageSelector extends AbstractTool
{
    /**
     * @var string
     */
    protected $perPage;

    /**
     * @var string
     */
    protected $perPageName = '';

    /**
     * Create a new PerPageSelector instance.
     *
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->perPageName = $this->table->model()->getPerPageName();
        $this->perPage = (int) request($this->perPageName, $this->table->perPage);
    }

    /**
     * Get options for selector.
     *
     * @return static
     */
    protected function getOptions()
    {
        return collect($this->table->perPages)
            ->push($this->table->perPage, $this->perPage)
            ->unique()
            ->sort();
    }

    /**
     * Render PerPageSelector。
     *
     * @return string
     */
    public function render()
    {
        return Admin::view('admin::table.perpage-selector', [
            'name'      => $this->perPageName,
            'perpage'   => $this->perPage,
            'options'   => $this->getOptions(),
        ]);
    }
}
