<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handler;
use Encore\Admin\Table\Column;
use Encore\Admin\Table\Concerns;
use Encore\Admin\Table\Displayers;
use Encore\Admin\Table\Model;
use Encore\Admin\Table\Row;
use Encore\Admin\Table\Tools;
use Encore\Admin\Traits\ShouldSnakeAttributes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Jenssegers\Mongodb\Eloquent\Model as MongodbModel;

class Table
{
    use Concerns\HasElementNames,
        Concerns\HasHeader,
        Concerns\HasFooter,
        Concerns\HasFilter,
        Concerns\HasTools,
        Concerns\HasTotalRow,
        Concerns\HasHotKeys,
        Concerns\HasQuickCreate,
        Concerns\HasActions,
        Concerns\HasSelector,
        Concerns\CanHidesColumns,
        Concerns\CanFixHeader,
        Concerns\CanFixColumns,
        Concerns\CanExportTable,
        ShouldSnakeAttributes,
        Macroable {
            __call as macroCall;
        }

    /**
     * The table data model instance.
     *
     * @var \Encore\Admin\Table\Model|\Illuminate\Database\Eloquent\Builder
     */
    protected $model;

    /**
     * Collection of all table columns.
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
     * All column names of the table.
     *
     * @var array
     */
    public $columnNames = [];

    /**
     * Mark if the table is builded.
     *
     * @var bool
     */
    protected $builded = false;

    /**
     * All variables in table view.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Resource path of the table.
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
     * View for table to render.
     *
     * @var string
     */
    protected $view = 'admin::table.table';

    /**
     * Per-page options.
     *
     * @var array
     */
    public $perPages = [10, 20, 30, 50, 100];

    /**
     * Default items count per-page.
     *
     * @var int
     */
    public $perPage = 20;

    /**
     * @var []callable
     */
    protected $renderingCallbacks = [];

    /**
     * Options for table.
     *
     * @var array
     */
    protected $options = [
        'show_pagination'        => true,
        'show_tools'             => true,
        'show_filter'            => true,
        'show_exporter'          => true,
        'show_actions'           => true,
        'show_row_selector'      => true,
        'show_create_btn'        => true,
        'show_column_selector'   => true,
        'show_perpage_selector'  => true,
    ];

    /**
     * @var string
     */
    public $tableID;

    /**
     * Initialization closure array.
     *
     * @var []Closure
     */
    protected static $initCallbacks = [];

    /**
     * @var bool
     */
    public $modalForm = false;

    /**
     * Create a new table instance.
     *
     * @param Eloquent $model
     */
    public function __construct(Eloquent $model)
    {
        $this->model = new Model($model, $this);
        $this->keyName = $model->getKeyName();

        $this->initialize();

        $this->callInitCallbacks();
    }

    /**
     * Initialize.
     */
    protected function initialize()
    {
        $this->tableID = uniqid('table-');

        $this->columns = Collection::make();
        $this->rows = Collection::make();

        $this->initTools()->initFilter();
    }

    /**
     * Initialize with user pre-defined default disables and exporter, etc.
     *
     * @param Closure $callback
     */
    public static function init(Closure $callback = null)
    {
        static::$initCallbacks[] = $callback;
    }

    /**
     * Call the initialization closure array in sequence.
     */
    protected function callInitCallbacks()
    {
        if (empty(static::$initCallbacks)) {
            return;
        }

        foreach (static::$initCallbacks as $callback) {
            call_user_func($callback, $this);
        }
    }

    /**
     * Get or set option for table.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this|mixed
     */
    public function option($key, $value = null)
    {
        if (is_null($value)) {
            return $this->options[$key];
        }

        $this->options[$key] = $value;

        return $this;
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
     * @return $this
     */
    public function modalForm()
    {
        $this->modalForm = true;

        return $this;
    }

    /**
     * Add a column to Table.
     *
     * @param string $name
     * @param string $label
     *
     * @return Column
     */
    public function column($name, $label = '')
    {
        if (Str::contains($name, '.')) {
            return $this->addRelationColumn($name, $label);
        }

        if (Str::contains($name, '->')) {
            return $this->addJsonColumn($name, $label);
        }

        return $this->__call($name, array_filter([$label]));
    }

    /**
     * Batch add column to table.
     *
     * @example
     * 1.$table->columns(['name' => 'Name', 'email' => 'Email' ...]);
     * 2.$table->columns('name', 'email' ...)
     *
     * @param array $columns
     *
     * @return Collection|null
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
     * Add column to table.
     *
     * @param string $column
     * @param string $label
     *
     * @return Column
     */
    protected function addColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setTable($this);

        return tap($column, function ($value) {
            $this->columns->push($value);
        });
    }

    /**
     * Get all columns object.
     *
     * @return Collection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add a relation column to table.
     *
     * @param string $name
     * @param string $label
     *
     * @return $this|bool|Column
     */
    protected function addRelationColumn($name, $label = '')
    {
        list($relation, $column) = explode('.', $name);

        $model = $this->model()->eloquent();

        if (!method_exists($model, $relation) || !$model->{$relation}() instanceof Relations\Relation) {
            $class = get_class($model);

            admin_error("Call to undefined relationship [{$relation}] on model [{$class}].");

            return $this;
        }

        $name = ($this->shouldSnakeAttributes() ? Str::snake($relation) : $relation).'.'.$column;

        $this->model()->with($relation);

        return $this->addColumn($name, $label)->setRelation($relation, $column);
    }

    /**
     * Add a json type column to table.
     *
     * @param string $name
     * @param string $label
     *
     * @return Column
     */
    protected function addJsonColumn($name, $label = '')
    {
        $column = substr($name, strrpos($name, '->') + 2);

        $name = str_replace('->', '.', $name);

        return $this->addColumn($name, $label ?: ucfirst($column));
    }

    /**
     * Prepend column to table.
     *
     * @param string $column
     * @param string $label
     *
     * @return Column
     */
    public function prependColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setTable($this);

        return tap($column, function ($value) {
            $this->columns->prepend($value);
        });
    }

    /**
     * Get Table model.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Paginate the table.
     *
     * @param int $perPage
     *
     * @return $this
     */
    public function paginate($perPage = 20)
    {
        $this->perPage = $perPage;

        $this->model()->setPerPage($perPage);

        return $this;
    }

    /**
     * Get the table paginator.
     *
     * @return mixed
     */
    public function paginator()
    {
        return new Tools\Paginator($this, $this->options['show_perpage_selector']);
    }

    /**
     * Disable table pagination.
     *
     * @return $this
     */
    public function disablePagination(bool $disable = true)
    {
        $this->model->usePaginate(!$disable);

        return $this->option('show_pagination', !$disable);
    }

    /**
     * If this table use pagination.
     *
     * @return bool
     */
    public function showPagination()
    {
        return $this->option('show_pagination');
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
     * @param bool $disable
     *
     * @return $this
     */
    public function disablePerPageSelector(bool $disable = true)
    {
        return $this->option('show_perpage_selector', !$disable);
    }

    /**
     * Disable row selector.
     *
     * @return Table|mixed
     */
    public function disableRowSelector(bool $disable = true)
    {
        return $this->disableBatchActions($disable);
    }

    /**
     * Prepend checkbox column for table.
     *
     * @return void
     */
    protected function prependRowSelectorColumn()
    {
        if (!$this->option('show_row_selector')) {
            return;
        }

        admin_assets_require('icheck');

        $check = <<<'HTML'
<div class='icheck-%s d-inline'>
    <input type="checkbox" class="table-select-all" id='select-all'/>
    <label for='select-all'></label>
</div>
HTML;

        $this->prependColumn(Column::SELECT_COLUMN_NAME, ' ')
            ->displayUsing(Displayers\RowSelector::class)
            ->addHeader(admin_color($check));
    }

    /**
     * Apply column filter to table query.
     *
     * @return void
     */
    protected function applyColumnFilter()
    {
        $this->columns->each->bindFilterQuery($this->model());
    }

    /**
     * Apply column search to table query.
     *
     * @return void
     */
    protected function applyColumnSearch()
    {
        $this->columns->each->bindSearchQuery($this->model());
    }

    /**
     * @return array|Collection|mixed
     */
    public function applyQuery()
    {
        $this->applyQuickSearch();

        $this->applyColumnFilter();

        $this->applyColumnSearch();

        $this->applySelectorQuery();
    }

    /**
     * Add row selector columns and action columns before and after the table.
     *
     * @return void
     */
    protected function addDefaultColumns()
    {
        $this->prependRowSelectorColumn();

        $this->appendActionsColumn();
    }

    /**
     * Build the table.
     *
     * @return void
     */
    public function build()
    {
        if ($this->builded) {
            return;
        }

        $this->applyQuery();

        $collection = $this->applyFilter(false);

        $this->addDefaultColumns();

        Column::setOriginalTableModels($collection);

        $data = $collection->toArray();

        $this->columns->map(function (Column $column) use (&$data) {
            $data = $column->fill($data);

            $this->columnNames[] = $column->getName();
        });

        $this->buildRows($data, $collection);

        $this->builded = true;
    }

    /**
     * Build the table rows.
     *
     * @param array $data
     *
     * @return void
     */
    protected function buildRows(array $data, Collection $collection)
    {
        $this->rows = collect($data)->map(function ($model, $number) use ($collection) {
            return new Row($number, $model, $collection->get($number)->getKey());
        });

        if ($this->rowsCallback) {
            $this->rows->map($this->rowsCallback);
        }
    }

    /**
     * Set table row callback function.
     *
     * @param Closure $callable
     *
     * @return Collection|null
     */
    public function rows(Closure $callable = null)
    {
        if (is_null($callable)) {
            return $this->rows;
        }

        $this->rowsCallback = $callable;
    }

    /**
     * Get create url.
     *
     * @return string
     */
    public function getCreateUrl()
    {
        $queryString = '';

        if ($constraints = $this->model()->getConstraints()) {
            $queryString = http_build_query($constraints);
        }

        return sprintf(
            '%s/create%s',
            $this->resource(),
            $queryString ? ('?'.$queryString) : ''
        );
    }

    /**
     * Remove create button on table.
     *
     * @return $this
     */
    public function disableCreateButton(bool $disable = true)
    {
        return $this->option('show_create_btn', !$disable);
    }

    /**
     * If allow creation.
     *
     * @return bool
     */
    public function showCreateBtn()
    {
        return $this->option('show_create_btn');
    }

    /**
     * Render create button for table.
     *
     * @return string
     */
    public function renderCreateButton()
    {
        return (new Tools\CreateButton($this))->render();
    }

    /**
     * Get current resource url.
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

        return url(app('request')->getPathInfo());
    }

    /**
     * Handle get mutator column for table.
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
     * Handle relation column for table.
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

        if (!($relation = $model->$method()) instanceof Relations\Relation) {
            return false;
        }

        if ($relation instanceof Relations\HasOne ||
            $relation instanceof Relations\BelongsTo ||
            $relation instanceof Relations\MorphOne
        ) {
            $this->model()->with($method);

            return $this->addColumn($method, $label)->setRelation(
                $this->shouldSnakeAttributes() ? Str::snake($method) : $method
            );
        }

        if ($relation instanceof Relations\HasMany
            || $relation instanceof Relations\BelongsToMany
            || $relation instanceof Relations\MorphToMany
            || $relation instanceof Relations\HasManyThrough
        ) {
            $this->model()->with($method);

            return $this->addColumn($this->shouldSnakeAttributes() ? Str::snake($method) : $method, $label);
        }

        return false;
    }

    /**
     * Dynamically add columns to the table view.
     *
     * @param $method
     * @param $arguments
     *
     * @return Column
     */
    public function __call($method, $arguments)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        $label = $arguments[0] ?? null;

        if ($this->model()->eloquent() instanceof MongodbModel) {
            return $this->addColumn($method, $label);
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
     * Add variables to table view.
     *
     * @param array $variables
     *
     * @return $this
     */
    public function with($variables = [])
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * Get all variables will used in table view.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['table'] = $this;

        return $this->variables;
    }

    /**
     * Set a view to render.
     *
     * @param string $view
     * @param array  $variables
     */
    public function setView($view, $variables = [])
    {
        if (!empty($variables)) {
            $this->with($variables);
        }

        $this->view = $view;
    }

    /**
     * Set table title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->variables['title'] = $title;

        return $this;
    }

    /**
     * Set relation for table.
     *
     * @param Relations\Relation $relation
     *
     * @return $this
     */
    public function setRelation(Relations\Relation $relation)
    {
        $this->model()->setRelation($relation);

        return $this;
    }

    /**
     * Set resource path for table.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setResource($path)
    {
        $this->resourcePath = $path;

        return $this;
    }

    /**
     * Set rendering callback.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function rendering(callable $callback)
    {
        $this->renderingCallbacks[] = $callback;

        return $this;
    }

    /**
     * Call callbacks before render.
     *
     * @return void
     */
    protected function callRenderingCallback()
    {
        foreach ($this->renderingCallbacks as $callback) {
            call_user_func($callback, $this);
        }
    }

    /**
     * Get the string contents of the table view.
     *
     * @return string
     */
    public function render()
    {
        $this->handleExportRequest(true);

        try {
            $this->build();
        } catch (\Exception $e) {
            return Handler::renderException($e);
        }

        $this->callRenderingCallback();

        $this->with(['__table' => "$('#{$this->tableID}')"]);

        return Admin::view($this->view, $this->variables());
    }
}
