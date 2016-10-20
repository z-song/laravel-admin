<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handle;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Exporter;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Model;
use Encore\Admin\Grid\Row;
use Encore\Admin\Pagination\AdminThreePresenter;
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
     * @var Exporter
     */
    protected $exporter;

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
        //$label = $label ?: Str::upper($column);

        return $this->columns[] = new Column($column, $label);
    }

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
    public function paginate($perPage = null)
    {
        $this->model()->paginate($perPage);
    }

    /**
     * Get the grid paginator.
     *
     * @return mixed
     */
    public function paginator()
    {
        $query = Input::all();

        return $this->model()->eloquent()->appends($query)->render(
            new AdminThreePresenter($this->model()->eloquent())
        );
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

        call_user_func($this->builder, $this);

        $data = $this->filter->execute();

        $this->columns->map(function (Column $column) use (&$data) {
            $data = $column->map($data);

            $this->columnNames[] = $column->getName();
        });

        $this->buildRows($data);

        $this->builded = true;
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
        $this->filter = new Filter($this->model());
    }

    /**
     * Setup grid exporter.
     *
     * @return void
     */
    protected function setupExporter()
    {
        $this->exporter = new Exporter($this);
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
     */
    public function disableBatchDeletion()
    {
        $this->allowBatchDeletion = false;
    }

    /**
     * Disable creation.
     */
    public function disableCreation()
    {
        $this->allowCreation = false;
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
     */
    public function disableActions()
    {
        $this->allowActions = false;
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
        return $this->filter->render();
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

    public function pathOfCreate()
    {
        $path = $query = '';

        extract(parse_url($this->resource()));

        return '/'.trim($path, '/').'/create'.$query;
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

        return view('admin::grid', $this->variables())->render();
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
        if ($this->model()->eloquent() instanceof MongodbModel) {
            $label = isset($arguments[0]) ? $arguments[0] : ucfirst($method);

            return $this->addColumn($method, $label);
        }

        $connection = $this->model()->eloquent()->getConnectionName();
        if (Schema::connection($connection)->hasColumn($this->model()->getTable(), $method)) {
            $label = isset($arguments[0]) ? $arguments[0] : ucfirst($method);

            return $this->addColumn($method, $label);
        }

        $relation = $this->model()->eloquent()->$method();

        if ($relation instanceof HasOne || $relation instanceof BelongsTo) {
            $this->model()->with($method);

            return $this->addColumn()->setRelation($method);
        }

        if ($relation instanceof HasMany || $relation instanceof BelongsToMany || $relation instanceof MorphToMany) {
            $this->model()->with($method);

            return $this->addColumn($method);
        }
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
