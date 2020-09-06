<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Table;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Class TableAction.
 *
 * @method retrieveModel(Request $request)
 */
abstract class TableAction extends Action
{
    /**
     * @var Table
     */
    protected $parent;

    /**
     * @var string
     */
    public $selectorPrefix = '.table-action-';

    /**
     * @param Table $table
     *
     * @return $this
     */
    public function setTable(Table $table)
    {
        $this->parent = $table;

        return $this;
    }

    /**
     * Get url path of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->parent->resource();
    }

    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        $model = $this->parent->model()->getOriginalModel();

        return str_replace('\\', '_', get_class($model));
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return ['_model' => $this->getModelClass()];
    }

    /**
     * Indicates if model uses soft-deletes.
     *
     * @param $modelClass
     *
     * @return bool
     */
    protected function modelUseSoftDeletes($modelClass)
    {
        return in_array(SoftDeletes::class, class_uses_deep($modelClass));
    }
}
