<?php

namespace Encore\Admin\Grid\Exporters;

use Encore\Admin\Grid;

abstract class AbstractExporter implements ExporterInterface
{
    /**
     * @var \Encore\Admin\Grid
     */
    protected $grid;

    /**
     * Create a new exporter instance.
     *
     * @param $grid
     */
    public function __construct(Grid $grid = null)
    {
        if ($grid) {
            $this->setGrid($grid);
        }
    }

    /**
     * Set grid for exporter.
     *
     * @param Grid $grid
     *
     * @return $this
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get table of grid.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->grid->model()->eloquent()->getTable();
    }

    /**
     * Get data with export query.
     *
     * @return array
     */
    public function getData()
    {
        return $this->grid->getFilter()->execute();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function export();
}
