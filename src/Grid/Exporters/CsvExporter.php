<?php

namespace Encore\Admin\Grid\Exporters;

use Illuminate\Support\Arr;

class CsvExporter extends AbstractExporter
{
    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $titles = [];

        $filename = $this->getTable().'.csv';

        $data = $this->getData();

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
