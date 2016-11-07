<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid;

class Exporter
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->grid->model()->usePaginate(false);
    }

    /**
     * Export csv file.
     *
     * @return mixed
     */
    public function export()
    {
        $titles = [];

        $filename = $this->grid->model()->eloquent()->getTable() . '.csv';

        $data = $this->grid->processFilter();

        if (!empty($data)) {
            $titles = array_keys(array_dot($data[0]));
        }

        $output = join(',', $titles) . "\n";

        foreach ($data as $row) {
            $output .= implode(',', array_dot($row)) . "\n";
        }

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;
    }
}
