<?php

namespace Encore\Admin\Grid\Exporters;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

abstract class ExcelExporter extends AbstractExporter implements FromQuery, WithHeadings
{
    use Exportable;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $headings = [];

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function query()
    {
        return $this->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $this->download($this->fileName)->prepare(request())->send();

        exit;
    }
}
