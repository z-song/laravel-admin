<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use ReflectionClass;

/**
 * Class Filter.
 *
 * @method Filter     equal($column, $label = '')
 * @method Filter     like($column, $label = '')
 * @method Filter     gt($column, $label = '')
 * @method Filter     lt($column, $label = '')
 * @method Filter     between($column, $label = '')
 * @method Filter     where(\Closure $callback, $label)
 */
class Filter
{
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
    protected $supports = ['equal', 'is', 'like', 'gt', 'lt', 'between', 'where'];

    /**
     * If use a modal to hold the filters.
     *
     * @var bool
     */
    protected $useModal = false;

    /**
     * If use id filter.
     *
     * @var bool
     */
    protected $useIdFilter = true;

    /**
     * Action of search form.
     *
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $view = 'admin::grid.filter';

    /**
     * Create a new filter instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
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
     * Set action of search form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Disable Id filter.
     */
    public function disableIdFilter()
    {
        $this->useIdFilter = false;
    }

    /**
     * Get all conditions of the filters.
     *
     * @return array
     */
    public function conditions()
    {
        $inputs = array_dot(Input::all());

        $inputs = array_filter($inputs, function ($input) {
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
        if (!$this->useIdFilter) {
            array_shift($this->filters);
        }

        if (empty($this->filters)) {
            return '';
        }

        if ($this->useModal) {

            $this->view = 'admin::filter.modal';

            $script = <<<'EOT'

$("#filter-modal .submit").click(function () {
    $("#filter-modal").modal('toggle');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});

EOT;
            Admin::script($script);
        }

        return view($this->view)->with([
            'action'    => $this->action ?: $this->urlWithoutFilters(),
            'filters'   => $this->filters,
        ]);
    }

    /**
     * Get url without filter queryString.
     *
     * @return string
     */
    protected function urlWithoutFilters()
    {
        $columns = [];

        /** @var Filter\AbstractFilter $filter **/
        foreach ($this->filters as $filter) {
            $columns[] = $filter->getColumn();
        }

        /** @var \Illuminate\Http\Request $request **/
        $request = Request::instance();

        $query = $request->query();
        array_forget($query, $columns);

        $question = $request->getBaseUrl().$request->getPathInfo() == '/' ? '/?' : '?';

        return count($request->query()) > 0
            ? $request->url().$question.http_build_query($query)
            : $request->fullUrl();
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
