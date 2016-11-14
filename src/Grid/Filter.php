<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
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
 * @method Filter     where(\Closure $callback, $label)
 */
class Filter
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $supports = ['is', 'like', 'gt', 'lt', 'between', 'where'];

    /**
     * @var bool
     */
    protected $useModal = false;

    /**
     * Create a new filter instance.
     *
     * @param Grid  $grid
     * @param Model $model
     */
    public function __construct(Grid $grid, Model $model)
    {
        $this->grid = $grid;

        $this->model = $model;

        $this->is($this->model->eloquent()->getKeyName());
    }

    /**
     * Use modal to show filter form.
     */
    public function useModal()
    {
        $this->useModal = true;
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
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if ($this->useModal) {
            return $this->renderModalFilter();
        }

        return view('admin::grid.filter')->with(['filters' => $this->filters(), 'grid' => $this->grid]);
    }

    /**
     * Render modal filter.
     *
     * @return $this
     */
    public function renderModalFilter()
    {
        $script = <<<'EOT'

$("#filter-modal .submit").click(function () {
    $("#filter-modal").modal('toggle');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});

EOT;
        Admin::script($script);

        return view('admin::filter.modal')->with(['filters' => $this->filters(), 'grid' => $this->grid]);
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
        if (in_array($method, $this->supports)) {
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
