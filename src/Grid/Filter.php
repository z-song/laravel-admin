<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Support\Facades\Input;
use ReflectionClass;

/**
 * Class Filter.
 *
 * @method Filter     is($column, $label = '')
 * @method Filter     like($column, $label = '')
 * @method Filter     gt($column, $label = '')
 * @method Filter     lt($column, $label = '')
 * @method Filter     between($column, $label = '')
 */
class Filter
{
    /**
     * @var
     */
    protected $model;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $allows = ['is', 'like', 'gt', 'lt', 'between'];

    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->is($this->model->eloquent()->getKeyName());
    }

    /**
     * Get all conditions of the filters.
     *
     * @return array
     */
    public function conditions()
    {
        $inputs = array_filter(Input::all(), function ($input) {
            return $input !== '';
        });

        $conditions = [];

        foreach ($this->filters() as $filter) {
            $conditions[] = $filter->condition($inputs);
        }

        return array_filter($conditions);
    }

    /**
     * Add a filter to grid.
     *
     * @param AbstractFilter $filter
     *
     * @return AbstractFilter
     */
    protected function addFilter(AbstractFilter $filter)
    {
        return $this->filters[] = $filter;
    }

    /**
     * Get all filters.
     *
     * @return AbstractFilter[]
     */
    protected function filters()
    {
        return $this->filters;
    }

    /**
     * Execute the filter with conditions.
     *
     * @return array
     */
    public function execute()
    {
        $this->model->addConditions($this->conditions());

        return $this->model->buildData();
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('admin::grid.filter')->with(['filters' => $this->filters()]);
    }

    /**
     * Generate a filter object and add to grid.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->allows)) {
            $className = '\\Encore\\Admin\\Grid\\Filter\\'.ucfirst($method);
            $reflection = new ReflectionClass($className);

            return $this->addFilter($reflection->newInstanceArgs($arguments));
        }
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function __toString()
    {
        return $this->render();
    }
}
