<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;

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
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid        = $grid;
        $this->perPageName = $this->grid->model()->getPerPageName();
        $this->perPage     = (int)request($this->perPageName, $this->grid->perPage);
    }

    /**
     * Get options for selector.
     *
     * @return static
     */
    protected function getOptions()
    {
        return collect($this->grid->perPages)
            ->push($this->grid->perPage, $this->perPage)
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
        return Admin::view('admin::grid.perpage-selector', [
            'name'      => $this->perPageName,
            'perpage'   => $this->perPage,
            'options'   => $this->getOptions(),
        ]);
    }
}
