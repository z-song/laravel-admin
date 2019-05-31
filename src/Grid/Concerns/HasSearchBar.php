<?php

namespace Encore\Admin\Grid\Concerns;

use Encore\Admin\Grid\Model;
use Encore\Admin\Grid\Tools\SearchBar;

trait HasSearchBar
{
    /**
     * @var string
     */
    public static $searchKey = '__search__';

    /**
     * @var array|string|\Closure
     */
    protected $search;

    /**
     * @param array|string|\Closure
     * @return $this
     */
    public function search($search)
    {
        if (func_num_args() > 1) {
            $this->search = func_get_args();
        } else {
            $this->search = $search;
        }

        if ($query = request()->get(static::$searchKey)) {
            $this->applySearch($query);
        }

        $this->tools->append(new SearchBar());

        return $this;
    }

    /**
     * Apply the search query to the query.
     *
     * @param string $query
     * @return mixed
     */
    protected function applySearch($query = '')
    {
        /** @var Model $model */
        $model = $this->model();

        if ($this->search instanceof \Closure) {
            return call_user_func($this->search, $model, $query);
        }

        if (is_string($this->search)) {
            $this->search = [$this->search];
        }

        if (is_array($this->search)) {
            $connectionType = $model->eloquent()->getConnection()->getDriverName();
            $likeOperator   = $connectionType == 'pgsql' ? 'ilike' : 'like';

            foreach ($this->search as $column) {
                $model->orWhere($column, $likeOperator, '%'.$query.'%');
            }
        }
    }
}