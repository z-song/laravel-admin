<?php

namespace Encore\Admin\Table\Column;

use Encore\Admin\Admin;
use Encore\Admin\Table\Model;

class InputFilter extends Filter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * InputFilter constructor.
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
     * @param string     $value
     * @param Model|null $model
     */
    public function addBinding($value, Model $model)
    {
        if (empty($value)) {
            return;
        }

        if ($this->type == 'like') {
            $model->where($this->getColumnName(), 'like', "%{$value}%");

            return;
        }

        if (in_array($this->type, ['date', 'time'])) {
            $method = 'where'.ucfirst($this->type);
            $model->{$method}($this->getColumnName(), $value);

            return;
        }

        $model->where($this->getColumnName(), $value);
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

        return Admin::view('admin::table.column.input-filter', [
            'class'  => uniqid('column-filter-'),
            'action' => $this->getFormAction(),
            'name'   => $this->getColumnName(),
            'value'  => $this->getFilterValue(),
            'dp'     => $dp,
        ]);
    }
}
