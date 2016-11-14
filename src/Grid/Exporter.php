<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid;
use Illuminate\Support\Arr;

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

        $filename = $this->grid->model()->eloquent()->getTable().'.csv';

        $data = $this->grid->processFilter();

        if (!empty($data)) {
            $columns = array_dot($this->sanitize($data[0]));

            $titles = array_keys($columns);
        }

        $output = implode(',', $titles)."\n";

        foreach ($data as $row) {
            $row = array_only($row, $titles);
            $output .= implode(',', array_dot($row))."\n";
        }

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;
    }

    /**
     * Remove indexed array.
     *
     * @param array $row
     *
     * @return array
     */
    protected function sanitize(array $row)
    {
        return collect($row)->reject(function ($val, $_) {
            return is_array($val) && !Arr::isAssoc($val);
        })->toArray();
    }
}
