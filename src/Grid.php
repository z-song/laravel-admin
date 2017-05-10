<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handle;
use Encore\Admin\Facades\Admin as AdminManager;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Exporter;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Model;
use Encore\Admin\Grid\Row;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Eloquent\Model as MongodbModel;

class Grid
{
    /**
     * The grid data model instance.
     *
     * @var \Encore\Admin\Grid\Model
     */
    protected $model;

    /**
     * Collection of all grid columns.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $columns;

    /**
     * Collection of all data rows.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $rows;

    /**
     * Rows callable fucntion.
     *
     * @var \Closure
     */
    protected $rowsCallback;

    /**
     * All column names of the grid.
     *
     * @var array
     */
    public $columnNames = [];

    /**
     * Grid builder.
     *
     * @var \Closure
     */
    protected $builder;

    /**
     * Mark if the grid is builded.
     *
     * @var bool
     */
    protected $builded = false;

    /**
     * All variables in grid view.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * The grid Filter.
     *
     * @var \Encore\Admin\Grid\Filter
     */
    protected $filter;

    /**
     * Resource path of the grid.
     *
     * @var
     */
    protected $resourcePath;

    /**
     * Default primary key name.
     *
     * @var string
     */
    protected $keyName = 'id';

    /**
     * Allow batch deletion.
     *
     * @var bool
     */
    protected $allowBatchDeletion = true;

    /**
     * Allow creation.
     *
     * @var bool
     */
    protected $allowCreation = true;

    /**
     * Allow actions.
     *
     * @var bool
     */
    protected $allowActions = true;

    /**
     * Allow export data.
     *
     * @var bool
     */
    protected $allowExport = true;

    /**
     * If use grid filter.
     *
     * @var bool
     */
    protected $useFilter = true;

    /**
     * If grid use pagination.
     *
     * @var bool
     */
    protected $usePagination = true;

    /**
     * If grid use per-page selector.
     *
     * @var bool
     */
    protected $usePerPageSelector = true;

    /**
     * Is grid rows orderable.
     *
     * @var bool
     */
    protected $orderable = false;

    /**
     * @var Exporter
     */
    protected $exporter;

    /**
     * View for grid to render.
     *
     * @var string
     */
    protected $view = 'admin::grid.table';

    /**
     * Per-page options.
     *
     * @var array
     */
    protected $perPages = [10, 20, 30, 50, 100];

    /**
     * Default items count per-page.
     *
     * @var int
     */
    protected $perPage = 20;

    /**
     * Create a new grid instance.
     *
     * @param Eloquent $model
     * @param callable $builder
     */
    public function __construct(Eloquent $model, Closure $builder)
    {
        $this->keyName = $model->getKeyName();
        $this->model = new Model($model);
        $this->columns = new Collection();
        $this->rows = new Collection();
        $this->builder = $builder;

        $this->setupFilter();
        $this->setupExporter();
    }

    /**
     * Get primary key name of model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName ?: 'id';
    }

    /**
     * Add column to Grid.
     *
     * @param string $name
     * @param string $label
     *
     * @return Column
     */
    public function column($name, $label = '')
    {
        $relationName = $relationColumn = '';

        if (strpos($name, '.') !== false) {
            list($relationName, $relationColumn) = explode('.', $name);

            $relation = $this->model()->eloquent()->$relationName();

            $label = empty($label) ? ucfirst($relationColumn) : $label;
        }

        $column = $this->addColumn($name, $label);

        if (isset($relation) && $relation instanceof Relation) {
            $this->model()->with($relationName);
            $column->setRelation($relation, $relationColumn);
        }

        return $column;
    }

    /**
     * Batch add column to grid.
     *
     * @example
     * 1.$grid->columns(['name' => 'Name', 'email' => 'Email' ...]);
     * 2.$grid->columns('name', 'email' ...)
     *
     * @param array $columns
     *
     * @return Collection|void
     */
    public function columns($columns = [])
    {
        if (func_num_args() == 0) {
            return $this->columns;
        }

        if (func_num_args() == 1 && is_array($columns)) {
            foreach ($columns as $column => $label) {
                $this->column($column, $label);
            }

            return;
        }

        foreach (func_get_args() as $column) {
            $this->column($column);
        }
    }

    /**
     * Add column to grid.
     *
     * @param string $column
     * @param string $label
     *
     * @return Column
     */
    protected function addColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setGrid($this);

        return $this->columns[] = $column;
    }

    /**
     * Add a blank column.
     *
     * @param $label
     *
     * @return Column
     */
    public function blank($label)
    {
        return $this->addColumn('blank', $label);
    }

    /**
     * Get Grid model.
     *
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Paginate the grid.
     *
     * @param int $perPage
     *
     * @return void
     */
    public function paginate($perPage = 20)
    {
        $this->perPage = $perPage;

        $this->model()->paginate($perPage);
    }

    /**
     * Get the grid paginator.
     *
     * @return mixed
     */
    public function paginator()
    {
        if (!$this->usePagination) {
            return '';
        }

        $query = Input::all();

        return $this->model()->eloquent()->appends($query)->render('admin::pagination');
    }

    /**
     * Build the grid.
     *
     * @return void
     */
    public function build()
    {
        if ($this->builded) {
            return;
        }

        $data = $this->processFilter();

        Column::setOriginalGridData($data);

        $this->columns->map(function (Column $column) use (&$data) {
            $data = $column->fill($data);

            $this->columnNames[] = $column->getName();
        });

        $this->buildRows($data);

        $this->builded = true;
    }

    /**
     * Process the grid filter.
     *
     * @return array
     */
    public function processFilter()
    {
        call_user_func($this->builder, $this);

        return $this->filter->execute();
    }

    /**
     * Build the grid rows.
     *
     * @param array $data
     *
     * @return void
     */
    protected function buildRows(array $data)
    {
        $this->rows = collect($data)->map(function ($val, $key) {
            $row = new Row($key, $val);

            $row->setKeyName($this->keyName);
            $row->setPath($this->resource());

            return $row;
        });

        if ($this->rowsCallback) {
            $this->rows->map($this->rowsCallback);
        }
    }

    /**
     * Set grid row callback function.
     *
     * @param callable $callable
     *
     * @return Collection|void
     */
    public function rows(Closure $callable = null)
    {
        if (is_null($callable)) {
            return $this->rows;
        }

        $this->rowsCallback = $callable;
    }

    /**
     * Setup grid filter.
     *
     * @return void
     */
    protected function setupFilter()
    {
        $this->filter = new Filter($this, $this->model());
    }

    /**
     * Setup grid exporter.
     *
     * @return void
     */
    protected function setupExporter()
    {
        if (Input::has('_export')) {
            $exporter = new Exporter($this);

            $exporter->export();
        }
    }

    /**
     * Export url.
     *
     * @return string
     */
    public function exportUrl()
    {
        $query = $query = Input::all();
        $query['_export'] = true;

        return $this->resource().'?'.http_build_query($query);
    }

    /**
     * If allow batch delete.
     *
     * @return bool
     */
    public function allowBatchDeletion()
    {
        return $this->allowBatchDeletion;
    }

    /**
     * Disable batch deletion.
     *
     * @return $this
     */
    public function disableBatchDeletion()
    {
        $this->allowBatchDeletion = false;

        return $this;
    }

    /**
     * Disable creation.
     *
     * @return $this
     */
    public function disableCreation()
    {
        $this->allowCreation = false;

        return $this;
    }

    /**
     * If allow creation.
     *
     * @return bool
     */
    public function allowCreation()
    {
        return $this->allowCreation;
    }

    /**
     * If allow actions.
     *
     * @return bool
     */
    public function allowActions()
    {
        return $this->allowActions;
    }

    /**
     * Disable all actions.
     *
     * @return $this
     */
    public function disableActions()
    {
        $this->allowActions = false;

        return $this;
    }

    /**
     * If grid allows export.s.
     *
     * @return bool
     */
    public function allowExport()
    {
        return $this->allowExport;
    }

    /**
     * Disable export.
     *
     * @return $this
     */
    public function disableExport()
    {
        $this->allowExport = false;

        return $this;
    }

    /**
     * Disable grid filter.
     *
     * @return $this
     */
    public function disableFilter()
    {
        $this->useFilter = false;

        return $this;
    }

    /**
     * Disable grid pagination.
     *
     * @return $this
     */
    public function disablePagination()
    {
        $this->model->usePaginate(false);

        $this->usePagination = false;

        return $this;
    }

    /**
     * If this grid use pagination.
     *
     * @return bool
     */
    public function usePagination()
    {
        return $this->usePagination;
    }

    /**
     * Disable grid per-page selector.
     */
    public function disablePerPageSelector()
    {
        $this->usePerPageSelector = false;

        return $this;
    }

    /**
     * If this grid use per-page selector.
     *
     * @return bool
     */
    public function usePerPageSelector()
    {
        return $this->usePerPageSelector;
    }

    /**
     * Set grid as orderable.
     *
     * @return $this
     */
    public function orderable()
    {
        $this->orderable = true;

        return $this;
    }

    /**
     * Is the grid orderable.
     *
     * @return bool
     */
    public function isOrderable()
    {
        return $this->orderable;
    }

    /**
     * Set the grid filter.
     *
     * @param callable $callback
     */
    public function filter(Closure $callback)
    {
        call_user_func($callback, $this->filter);
    }

    /**
     * Render the grid filter.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderFilter()
    {
        if (!$this->useFilter) {
            return '';
        }

        return $this->filter->render();
    }

    /**
     * Set per-page options.
     *
     * @param array $perPages
     */
    public function perPages(array $perPages)
    {
        $this->perPages = $perPages;
    }

    /**
     * Generate per-page options.
     *
     * @return string
     */
    public function perPageOptions()
    {
        $perPage = (int) app('request')->input(
            $this->model->getPerPageName(),
            $this->perPage
        );

        return collect($this->perPages)
            ->push($this->perPage)
            ->push($perPage)
            ->unique()
            ->sort()
            ->map(function ($option) use ($perPage) {
                $selected = ($option == $perPage) ? 'selected' : '';
                $url = app('request')->fullUrlWithQuery([$this->model->getPerPageName() => $option]);

                return "<option value=\"$url\" $selected>$option</option>";
            })->implode("\r\n");
    }

    /**
     * Get current resource uri.
     *
     * @param string $path
     *
     * @return string
     */
    public function resource($path = null)
    {
        if (!empty($path)) {
            $this->resourcePath = $path;

            return $this;
        }

        if (!empty($this->resourcePath)) {
            return $this->resourcePath;
        }

        return app('router')->current()->getPath();
    }

    /**
     * Handle table column for grid.
     *
     * @param string $method
     * @param string $label
     *
     * @return bool|Column
     */
    protected function handleTableColumn($method, $label)
    {
        $connection = $this->model()->eloquent()->getConnectionName();

        if (Schema::connection($connection)->hasColumn($this->model()->getTable(), $method)) {
            return $this->addColumn($method, $label);
        }

        return false;
    }

    /**
     * Handle get mutator column for grid.
     *
     * @param string $method
     * @param string $label
     *
     * @return bool|Column
     */
    protected function handleGetMutatorColumn($method, $label)
    {
        if ($this->model()->eloquent()->hasGetMutator($method)) {
            return $this->addColumn($method, $label);
        }

        return false;
    }

    /**
     * Handle relation column for grid.
     *
     * @param string $method
     * @param string $label
     *
     * @return bool|Column
     */
    protected function handleRelationColumn($method, $label)
    {
        $model = $this->model()->eloquent();

        if (!method_exists($model, $method)) {
            return false;
        }

        if (!($relation = $model->$method()) instanceof Relation) {
            return false;
        }

        if ($relation instanceof HasOne || $relation instanceof BelongsTo) {
            $this->model()->with($method);

            return $this->addColumn($method, $label)->setRelation($method);
        }

        if ($relation instanceof HasMany || $relation instanceof BelongsToMany || $relation instanceof MorphToMany) {
            $this->model()->with($method);

            return $this->addColumn($method, $label);
        }

        return false;
    }

    /**
     * Dynamically add columns to the grid view.
     *
     * @param $method
     * @param $arguments
     *
     * @return $this|Column
     */
    public function __call($method, $arguments)
    {
        $label = isset($arguments[0]) ? $arguments[0] : ucfirst($method);

        if ($this->model()->eloquent() instanceof MongodbModel) {
            return $this->addColumn($method, $label);
        }

        if ($column = $this->handleTableColumn($method, $label)) {
            return $column;
        }

        if ($column = $this->handleGetMutatorColumn($method, $label)) {
            return $column;
        }

        if ($column = $this->handleRelationColumn($method, $label)) {
            return $column;
        }

        return $this->addColumn($method, $label);
    }

    /**
     * Add variables to grid view.
     *
     * @param array $variables
     *
     * @return $this
     */
    public function with($variables = [])
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Get all variables will used in grid view.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['grid'] = $this;

        return $this->variables;
    }

    /**
     * Set a view to render.
     *
     * @param string $view
     * @param array  $variables
     */
    public function view($view, $variables = [])
    {
        if (!empty($variables)) {
            $this->with($variables);
        }

        $this->view = $view;
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function render()
    {
        try {
            $this->build();
        } catch (\Exception $e) {
            return with(new Handle($e))->render();
        }

        AdminManager::script($this->script());

        return view($this->view, $this->variables())->render();
    }

    /**
     * Js code for grid.
     *
     * @return string
     */
    public function script()
    {
        $path = app('router')->current()->getPath();
        $token = csrf_token();
        $confirm = trans('admin::lang.delete_confirm');

        return <<<EOT

$('.grid-select-all').change(function() {
    if (this.checked) {
        $('.grid-item').prop("checked", true);
    } else {
        $('.grid-item').prop("checked", false);
    }
});

$('.batch-delete').on('click', function() {
    var selected = [];
    $('.grid-item:checked').each(function(){
        selected.push($(this).data('id'));
    });

    if (selected.length == 0) {
        return;
    }

    if(confirm("{$confirm}")) {
        $.post('/{$path}/' + selected.join(), {_method:'delete','_token':'{$token}'}, function(data){
            $.pjax.reload('#pjax-container');
            noty({
                text: "<strong>Succeeded!</strong>",
                type:'success',
                timeout: 1000
            });
        });
    }
});

$('.grid-refresh').on('click', function() {
    $.pjax.reload('#pjax-container');

    noty({
        text: "<strong>Succeeded!</strong>",
        type:'success',
        timeout: 1000
    });
});

var grid_order = function(id, direction) {
    $.post('/{$path}/' + id, {_method:'PUT', _token:'{$token}', _orderable:direction}, function(data){

        if (data.status) {
            noty({
                text: "<strong>Succeeded!</strong>",
                type:'success',
                timeout: 1000
            });

            $.pjax.reload('#pjax-container');
        }
    });
}

$('.grid-order-up').on('click', function() {
    grid_order($(this).data('id'), 1);
});

$('.grid-order-down').on('click', function() {
    grid_order($(this).data('id'), 0);
});

$('.per-page').select2({minimumResultsForSearch: -1}).on("select2:select", function(e) {
    $.pjax({url: this.value, container: '#pjax-container'});
});

EOT;
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
