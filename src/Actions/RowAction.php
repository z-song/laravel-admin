<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Grid\Column;
use Illuminate\Http\Request;

abstract class RowAction extends GridAction
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $row;

    /**
     * @var Column
     */
    protected $column;

    /**
     * @var string
     */
    public $selectorPrefix = '.grid-row-action-';

    /**
     * Get primary key value of current row.
     *
     * @return mixed
     */
    protected function getKey()
    {
        return $this->row->getKey();
    }

    /**
     * Set row model.
     *
     * @param mixed $key
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function row($key = null)
    {
        if (func_num_args() == 0) {
            return $this->row;
        }

        return $this->row->getAttribute($key);
    }

    /**
     * Set row model.
     *
     * @param \Illuminate\Database\Eloquent\Model $row
     *
     * @return $this
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param Column $column
     *
     * @return $this
     */
    public function setColumn(Column $column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return string
     */
    public function href()
    {
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function retrieveModel(Request $request)
    {
        if (!$key = $request->get('_key')) {
            return false;
        }

        $modelClass = str_replace('_', '\\', $request->get('_model'));

        return $modelClass::findOrFail($key);
    }

    /**
     * Render row action.
     *
     * @return string
     */
    public function render()
    {
        if (!$href = $this->href()) {
            $href = 'javascript:void(0);';

            $this->addScript();
        }

        $attributes = $this->formatAttributes();

        return sprintf(
            "<a data-_key='%s' href='%s' class='%s' {$attributes}>%s</a>",
            $this->getKey(),
            $href,
            $this->getElementClass(),
            $this->name()
        );
    }
}
