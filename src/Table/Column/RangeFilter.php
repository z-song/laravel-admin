<?php

namespace Encore\Admin\Table\Column;

use Encore\Admin\Admin;
use Encore\Admin\Table\Model;

class RangeFilter extends Filter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * RangeFilter constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Add a binding to the query.
     *
     * @param mixed $value
     * @param Model $model
     */
    public function addBinding($value, Model $model)
    {
        $value = array_filter((array) $value);

        if (empty($value)) {
            return;
        }

        if (!isset($value['start'])) {
            return $model->where($this->getColumnName(), '<', $value['end']);
        } elseif (!isset($value['end'])) {
            return $model->where($this->getColumnName(), '>', $value['start']);
        } else {
            return $model->whereBetween($this->getColumnName(), array_values($value));
        }
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $dpFormat = [
            'date'     => 'YYYY-MM-DD',
            'time'     => 'HH:mm:ss',
            'datetime' => 'YYYY-MM-DD HH:mm:ss',
        ];

        // datetimepicker options.
        $dp = isset($dpFormat[$this->type]) ? [
            'format'           => $dpFormat[$this->type],
            'locale'           => config('app.locale'),
            'allowInputToggle' => true,
            'icons'            => [
                'time' => 'fas fa-clock',
            ],
        ] : false;

        return Admin::view('admin::table.column.range-filter', [
            'action' => $this->getFormAction(),
            'value'  => $this->getFilterValue(['start' => '', 'end' => '']),
            'name'   => $this->getColumnName(),
            'dp'     => $dp,
            'class'  => [
                'start' => uniqid('column-filter-start-'),
                'end'   => uniqid('column-filter-end-'),
            ],
        ]);
    }
}
