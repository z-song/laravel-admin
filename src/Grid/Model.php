<?php

namespace Encore\Admin\Grid;

use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model
{
    /**
     * Eloquent model instance of the grid model.
     *
     * @var EloquentModel
     */
    protected $model;

    /**
     * Array of queries of the eloquent model.
     *
     * @var array
     */
    protected $queries = [];

    /**
     * Create a new grid model instance.
     *
     * @param EloquentModel $model
     */
    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get the eloquent model of the grid model.
     *
     * @return EloquentModel
     */
    public function eloquent()
    {
        return $this->model;
    }

    /**
     * Build
     *
     * @return array
     */
    public function buildData()
    {
        return $this->get()->getCollection()->toArray();
    }

    /**
     * Add conditions to grid model.
     *
     * @param array $conditions
     * @return void
     */
    public function addConditions(array $conditions)
    {
        foreach($conditions as $condition)
        {
            call_user_func_array([$this, key($condition)], current($condition));
        }
    }

    /**
     * Get table of the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    protected function get()
    {
        if($this->model instanceof Paginator) {
            return $this->model;
        }

        $this->setPaginate();

        foreach($this->queries as $method => $arguments)
        {
            $this->model = call_user_func_array([$this->model, $method], $arguments);
        }

        return $this->model;
    }

    /**
     * Set the grid paginate.
     *
     * @return void
     */
    protected function setPaginate()
    {
        $paginate = Arr::pull($this->queries, 'paginate');

        $this->queries['paginate'] = empty($paginate) ? [20] : $paginate;
    }

    public function __call($method, $arguments)
    {
        $this->queries[$method] = $arguments;

        return $this;
    }
}