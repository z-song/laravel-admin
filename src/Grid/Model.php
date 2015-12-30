<?php

namespace Encore\Admin\Grid;

use Illuminate\Pagination\Paginator;

class Model {

    protected $model;

    protected $queries = [];

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function buildData()
    {
        return $this->get()->getCollection()->toArray();
    }

    protected function get()
    {
        if($this->model instanceof Paginator)
        {
            return $this->model;
        }

        if( ! isset($this->queries['paginate'])) {
            $this->queries['paginate'] = [20];
        }

        foreach($this->queries as $method => $arguments)
        {
            $this->model = call_user_func_array([$this->model, $method], $arguments);
        }

        return $this->model;
    }

    public function __call($method, $arguments)
    {
        $this->queries[$method] = $arguments;

        return $this;
    }

}