<?php

namespace Encore\Admin\Table\Column;

use Encore\Admin\Admin;
use Encore\Admin\Table\Model;

class CheckFilter extends Filter
{
    /**
     * @var array
     */
    protected $options;

    /**
     * CheckFilter constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Add a binding to the query.
     *
     * @param array $value
     * @param Model $model
     */
    public function addBinding($value, Model $model)
    {
        if (empty($value)) {
            return;
        }

        $model->whereIn($this->getColumnName(), $value);
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        admin_assets_require('icheck');

        $value = $this->getFilterValue([]);

        return Admin::view('admin::table.column.check-filter', [
            'name'     => $this->getColumnName(),
            'action'   => $this->getFormAction(),
            'value'    => $value,
            'allCheck' => (count($value) == count($this->options)) ? 'checked' : '',
            'options'  => $this->options,
            'class'    => [
                'all'  => uniqid('column-filter-all-'),
                'item' => uniqid('column-filter-item-'),
            ],
        ]);
    }
}
