<?php

namespace Encore\Admin\Grid;

use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

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

    protected $sort;

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

        $this->setSort();
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

    /**
     * Set the grid sort.
     *
     * @return void
     */
    protected function setSort()
    {
        $this->sort = Input::get('_sort', []);
        if(! is_array($this->sort)) return;

        if(empty($this->sort['column']) || empty($this->sort['type'])) return;

        if(Str::contains($this->sort['column'], '.')) {
            list($relationName, $relationColumn) = explode('.', $this->sort['column']);

            if(isset($this->queries['with']) && in_array($relationName, $this->queries['with'])) {
                $this->queries['with'][$relationName] = function($relation) use ($relationColumn) {
                    $relation->orderBy($relationColumn, 'desc');
                };
            }
        }

        $this->queries['orderBy'] = [$this->sort['column'], $this->sort['type']];
    }

    public function __call($method, $arguments)
    {
        $this->queries[$method] = $arguments;

        return $this;
    }
}