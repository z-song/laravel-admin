<?php

namespace Encore\Admin\Grid\Exporters;

use Illuminate\Support\Str;

class CsvExporter extends AbstractExporter
{
    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $filename = $this->getTable().'.csv';

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        response()->stream(function () {

            $handle = fopen('php://output', 'w');

            $titles = [];

            $this->chunk(function ($records) use ($handle, &$titles) {

                if (empty($titles)) {
                    $titles = collect(array_dot($records->first()->toArray()))->keys()->map(function ($key) {
                        $key = str_replace('.', ' ', $key);

                        return Str::ucfirst($key);
                    });

                    // Add CSV headers
                    fputcsv($handle, $titles->toArray());
                }

                foreach ($records as $record) {
                    fputcsv($handle, array_dot($record->toArray()));
                }

            });

            // Close the output stream
            fclose($handle);

        }, 200, $headers)->send();

        exit;
    }
}
