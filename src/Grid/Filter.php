<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Grid\Filter\AbstractFilter;
use Encore\Admin\Grid\Filter\Scope;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

/**
 * Class Filter.
 *
 * @method AbstractFilter     equal($column, $label = '')
 * @method AbstractFilter     notEqual($column, $label = '')
 * @method AbstractFilter     like($column, $label = '')
 * @method AbstractFilter     ilike($column, $label = '')
 * @method AbstractFilter     gt($column, $label = '')
 * @method AbstractFilter     lt($column, $label = '')
 * @method AbstractFilter     between($column, $label = '')
 * @method AbstractFilter     in($column, $label = '')
 * @method AbstractFilter     notIn($column, $label = '')
 * @method AbstractFilter     where($callback, $label)
 * @method AbstractFilter     date($column, $label = '')
 * @method AbstractFilter     day($column, $label = '')
 * @method AbstractFilter     month($column, $label = '')
 * @method AbstractFilter     year($column, $label = '')
 * @method AbstractFilter     hidden($name, $value)
 */
class Filter implements Renderable
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
    protected $supports = [
        'equal', 'notEqual', 'ilike', 'like', 'gt', 'lt', 'between',
        'where', 'in', 'notIn', 'date', 'day', 'month', 'year', 'hidden',
    ];

    /**
     * If use id filter.
     *
     * @var bool
     */
    protected $useIdFilter = true;

    /**
     * Id filter was removed.
     *
     * @var bool
     */
    protected $idFilterRemoved = false;

    /**
     * Action of search form.
     *
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $view = 'admin::filter.container';

    /**
     * @var string
     */
    protected $filterID = 'filter-box';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var bool
     */
    public $expand = false;

    /**
     * @var Collection
     */
    protected $scopes;

    /**
     * Create a new filter instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $pk = $this->model->eloquent()->getKeyName();

        $this->equal($pk, strtoupper($pk));
        $this->scopes = new Collection();
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
     * Get grid model.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set ID of search form.
     *
     * @param string $filterID
     *
     * @return $this
     */
    public function setFilterID($filterID)
    {
        $this->filterID = $filterID;

        return $this;
    }

    /**
     * Get filter ID.
     *
     * @return string
     */
    public function getFilterID()
    {
        return $this->filterID;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        $this->setFilterID("{$this->name}-{$this->filterID}");

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Disable Id filter.
     */
    public function disableIdFilter()
    {
        $this->useIdFilter = false;
    }

    /**
     * Remove ID filter if needed.
     */
    public function removeIDFilterIfNeeded()
    {
        if (!$this->useIdFilter && !$this->idFilterRemoved) {
            array_shift($this->filters);
            $this->idFilterRemoved = true;
        }
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
            return $input !== '' && !is_null($input);
        });

        $this->sanitizeInputs($inputs);

        if (empty($inputs)) {
            return [];
        }

        $params = [];

        foreach ($inputs as $key => $value) {
            array_set($params, $key, $value);
        }

        $conditions = [];

        $this->removeIDFilterIfNeeded();

        foreach ($this->filters() as $filter) {
            $conditions[] = $filter->condition($params);
        }

        return tap(array_filter($conditions), function ($conditions) {
            if (!empty($conditions)) {
                $this->expand();
            }
        });
    }

    /**
     * @param $inputs
     *
     * @return array
     */
    protected function sanitizeInputs(&$inputs)
    {
        if (!$this->name) {
            return $inputs;
        }

        $inputs = collect($inputs)->filter(function ($input, $key) {
            return starts_with($key, "{$this->name}_");
        })->mapWithKeys(function ($val, $key) {
            $key = str_replace("{$this->name}_", '', $key);

            return [$key => $val];
        })->toArray();
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
        $filter->setParent($this);

        return $this->filters[] = $filter;
    }

    /**
     * Use a custom filter.
     *
     * @param AbstractFilter $filter
     *
     * @return AbstractFilter
     */
    public function use(AbstractFilter $filter)
    {
        return $this->addFilter($filter);
    }

    /**
     * Get all filters.
     *
     * @return AbstractFilter[]
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * @param string $key
     * @param string $label
     * @return mixed
     */
    public function scope($key, $label = '')
    {
        return tap(new Scope($key, $label), function (Scope $scope) {
            return $this->scopes->push($scope);
        });
    }

    /**
     * Get all filter scopes.
     *
     * @return Collection
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Get current scope.
     *
     * @return Scope|null
     */
    public function getCurrentScope()
    {
        $key = request(Scope::QUERY_NAME);

        return $this->scopes->first(function ($scope) use ($key) {
            return $scope->key == $key;
        });
    }

    /**
     * Get scope conditions.
     *
     * @return array
     */
    protected function scopeConditions()
    {
        if ($scope = $this->getCurrentScope()) {
            return $scope->condition();
        }

        return [];
    }

    /**
     * Expand filter container.
     *
     * @return $this
     */
    public function expand()
    {
        $this->expand = true;

        return $this;
    }

    /**
     * Execute the filter with conditions.
     *
     * @return array
     */
    public function execute()
    {
        $conditions = array_merge(
            $this->conditions(), $this->scopeConditions()
        );

        return $this->model->addConditions($conditions)->buildData();
    }

    /**
     * @param callable $callback
     * @param int      $count
     *
     * @return bool
     */
    public function chunk(callable $callback, $count = 100)
    {
        $conditions = array_merge(
            $this->conditions(), $this->scopeConditions()
        );

        return $this->model->addConditions($conditions)->chunk($callback, $count);
    }

    /**
     * Get the string contents of the filter view.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $this->removeIDFilterIfNeeded();

        if (empty($this->filters)) {
            return '';
        }

        return view($this->view)->with([
            'action'    => $this->action ?: $this->urlWithoutFilters(),
            'filters'   => $this->filters,
            'filterID'  => $this->filterID,
            'expand'    => $this->expand,
        ])->render();
    }

    /**
     * Get url without filter queryString.
     *
     * @return string
     */
    public function urlWithoutFilters()
    {
        $columns = collect($this->filters)->map->getColumn();

        return $this->fullUrlWithoutQuery($columns);
    }

    /**
     * Get url without scope queryString.
     *
     * @return string
     */
    public function urlWithoutScopes()
    {
        return $this->fullUrlWithoutQuery(Scope::QUERY_NAME);
    }

    /**
     * Get full url without query strings.
     *
     * @param Arrayable|array|string $keys
     * @return string
     */
    protected function fullUrlWithoutQuery($keys)
    {
        if ($keys instanceof Arrayable) {
            $keys = $keys->toArray();
        }

        $keys = (array) $keys;

        $request = request();

        $query = $request->query();
        array_forget($query, $keys);

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
     * @return AbstractFilter|$this
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->supports)) {
            $className = '\\Encore\\Admin\\Grid\\Filter\\'.ucfirst($method);

            return $this->addFilter(new $className(...$arguments));
        }

        return $this;
    }
}
