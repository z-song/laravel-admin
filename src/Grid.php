<?php

namespace Encore\Admin;

use Closure;
use Encore\Admin\Exception\Handler;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers;
use Encore\Admin\Grid\Exporter;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\HasElementNames;
use Encore\Admin\Grid\Model;
use Encore\Admin\Grid\Row;
use Encore\Admin\Grid\Tools;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Jenssegers\Mongodb\Eloquent\Model as MongodbModel;

class Grid
{
    use HasElementNames;

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
     * Export driver.
     *
     * @var string
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
    public $perPages = [10, 20, 30, 50, 100];

    /**
     * Default items count per-page.
     *
     * @var int
     */
    public $perPage = 20;

    /**
     * Header tools.
     *
     * @var Tools
     */
    public $tools;

    /**
     * Callback for grid actions.
     *
     * @var Closure
     */
    protected $actionsCallback;

    /**
     * Options for grid.
     *
     * @var array
     */
    protected $options = [
        'usePagination'  => true,
        'useTools'       => true,
        'useFilter'      => true,
        'useExporter'    => true,
        'useActions'     => true,
        'useRowSelector' => true,
        'allowCreate'    => true,
    ];

    /**
     * @var Closure
     */
    protected $footer;

    /**
     * Create a new grid instance.
     *
     * @param Eloquent $model
     * @param Closure  $builder
     */
    public function __construct(Eloquent $model, Closure $builder = null)
    {
        $this->keyName = $model->getKeyName();
        $this->model = new Model($model);
        $this->columns = new Collection();
        $this->rows = new Collection();
        $this->builder = $builder;

        $this->model()->setGrid($this);

        $this->setupTools();
        $this->setupFilter();

        $this->handleExportRequest();
    }

    /**
     * Setup grid tools.
     */
    public function setupTools()
    {
        $this->tools = new Tools($this);
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
     * Handle export request.
     *
     * @param bool $forceExport
     */
    protected function handleExportRequest($forceExport = false)
    {
        if (!$scope = request(Exporter::$queryName)) {
            return;
        }

        // clear output buffer.
        if (ob_get_length()) {
            ob_end_clean();
        }

        $this->model()->usePaginate(false);

        if ($this->builder) {
            call_user_func($this->builder, $this);

            $this->getExporter($scope)->export();
        }

        if ($forceExport) {
            $this->getExporter($scope)->export();
        }
    }

    /**
     * @param string $scope
     *
     * @return AbstractExporter
     */
    protected function getExporter($scope)
    {
        return (new Exporter($this))->resolve($this->exporter)->withScope($scope);
    }

    /**
     * Get or set option for grid.
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

            $name = snake_case($relationName).'.'.$relationColumn;
        }

        $column = $this->addColumn($name, $label);

        if (isset($relation) && $relation instanceof Relations\Relation) {
            $this->model()->with($relationName);
            $column->setRelation($relationName, $relationColumn);
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

        return tap($column, function ($value) {
            $this->columns->push($value);
        });
    }

    /**
     * Prepend column to grid.
     *
     * @param string $column
     * @param string $label
     *
     * @return Column
     */
    protected function prependColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setGrid($this);

        return tap($column, function ($value) {
            $this->columns->prepend($value);
        });
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
        return new Tools\Paginator($this);
    }

    /**
     * Disable grid pagination.
     *
     * @return $this
     */
    public function disablePagination()
    {
        $this->model->usePaginate(false);

        $this->option('usePagination', false);

        return $this;
    }

    /**
     * If this grid use pagination.
     *
     * @return bool
     */
    public function usePagination()
    {
        return $this->option('usePagination');
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
     * Disable all actions.
     *
     * @return $this
     */
    public function disableActions()
    {
        return $this->option('useActions', false);
    }

    /**
     * Set grid action callback.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function actions(Closure $callback)
    {
        $this->actionsCallback = $callback;

        return $this;
    }

    /**
     * Add `actions` column for grid.
     *
     * @return void
     */
    protected function appendActionsColumn()
    {
        if (!$this->option('useActions')) {
            return;
        }

        $this->addColumn('__actions__', trans('admin.action'))
            ->displayUsing(Displayers\Actions::class, [$this->actionsCallback]);
    }

    /**
     * Disable row selector.
     *
     * @return Grid|mixed
     */
    public function disableRowSelector()
    {
        $this->tools(function ($tools) {
            /* @var Grid\Tools $tools */
            $tools->disableBatchActions();
        });

        return $this->option('useRowSelector', false);
    }

    /**
     * Prepend checkbox column for grid.
     *
     * @return void
     */
    protected function prependRowSelectorColumn()
    {
        if (!$this->option('useRowSelector')) {
            return;
        }

        $this->prependColumn(Column::SELECT_COLUMN_NAME, ' ')
            ->displayUsing(Displayers\RowSelector::class);
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

        $collection = $this->processFilter(false);

        $data = $collection->toArray();

        $this->prependRowSelectorColumn();
        $this->appendActionsColumn();

        Column::setOriginalGridModels($collection);

        $this->columns->map(function (Column $column) use (&$data) {
            $data = $column->fill($data);

            $this->columnNames[] = $column->getName();
        });

        $this->buildRows($data);

        $this->builded = true;
    }

    /**
     * Disable header tools.
     *
     * @return $this
     */
    public function disableTools()
    {
        $this->option('useTools', false);
        return $this;
    }

    /**
     * Disable grid filter.
     *
     * @return $this
     */
    public function disableFilter()
    {
        $this->option('useFilter', false);

        $this->tools->disableFilterButton();

        return $this;
    }

    /**
     * Get filter of Grid.
     *
     * @return Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Process the grid filter.
     *
     * @param bool $toArray
     *
     * @return array|Collection|mixed
     */
    public function processFilter($toArray = true)
    {
        if ($this->builder) {
            call_user_func($this->builder, $this);
        }

        return $this->filter->execute($toArray);
    }

    /**
     * Set the grid filter.
     *
     * @param Closure $callback
     */
    public function filter(Closure $callback)
    {
        call_user_func($callback, $this->filter);
    }

    /**
     * Render the grid filter.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function renderFilter()
    {
        if (!$this->option('useFilter')) {
            return '';
        }

        return $this->filter->render();
    }

    /**
     * Expand filter.
     *
     * @return $this
     */
    public function expandFilter()
    {
        $this->filter->expand();

        return $this;
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
        $this->rows = collect($data)->map(function ($model, $number) {
            return new Row($number, $model);
        });

        if ($this->rowsCallback) {
            $this->rows->map($this->rowsCallback);
        }
    }

    /**
     * Set grid row callback function.
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
     * Setup grid tools.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function tools(Closure $callback)
    {
        call_user_func($callback, $this->tools);
    }

    /**
     * Render custom tools.
     *
     * @return string
     */
    public function renderHeaderTools()
    {
        return $this->tools->render();
    }

    /**
     * Set exporter driver for Grid to export.
     *
     * @param $exporter
     *
     * @return $this
     */
    public function exporter($exporter)
    {
        $this->exporter = $exporter;

        return $this;
    }

    /**
     * Get the export url.
     *
     * @param int  $scope
     * @param null $args
     *
     * @return string
     */
    public function getExportUrl($scope = 1, $args = null)
    {
        $input = array_merge(Input::all(), Exporter::formatExportQuery($scope, $args));

        if ($constraints = $this->model()->getConstraints()) {
            $input = array_merge($input, $constraints);
        }

        return $this->resource().'?'.http_build_query($input);
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

        return sprintf('%s/create%s',
            $this->resource(),
            $queryString ? ('?'.$queryString) : ''
        );
    }

    /**
     * If grid allows to use header tools
     *
     * @return bool
     */
    public function allowTools()
    {
        return $this->option('useTools');
    }

    /**
     * If grid allows export.s.
     *
     * @return bool
     */
    public function allowExport()
    {
        return $this->option('useExporter');
    }

    /**
     * Disable export.
     *
     * @return $this
     */
    public function disableExport()
    {
        return $this->option('useExporter', false);
    }

    /**
     * Render export button.
     *
     * @return string
     */
    public function renderExportButton()
    {
        return (new Tools\ExportButton($this))->render();
    }

    /**
     * Alias for method `disableCreateButton`.
     *
     * @return $this
     *
     * @deprecated
     */
    public function disableCreation()
    {
        return $this->disableCreateButton();
    }

    /**
     * Remove create button on grid.
     *
     * @return $this
     */
    public function disableCreateButton()
    {
        return $this->option('allowCreate', false);
    }

    /**
     * If allow creation.
     *
     * @return bool
     */
    public function allowCreation()
    {
        return $this->option('allowCreate');
    }

    /**
     * Render create button for grid.
     *
     * @return string
     */
    public function renderCreateButton()
    {
        return (new Tools\CreateButton($this))->render();
    }

    /**
     * Set grid footer.
     *
     * @param Closure|null $closure
     *
     * @return Closure
     */
    public function footer(Closure $closure = null)
    {
        if (!$closure) {
            return $this->footer;
        }

        $this->footer = $closure;

        return $this;
    }

    /**
     * Render grid footer.
     *
     * @return Tools\Footer|string
     */
    public function renderFooter()
    {
        if (!$this->footer) {
            return '';
        }

        return (new Tools\Footer($this))->render();
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

        return app('request')->getPathInfo();
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

        if (!($relation = $model->$method()) instanceof Relations\Relation) {
            return false;
        }

        if ($relation instanceof Relations\HasOne || $relation instanceof Relations\BelongsTo) {
            $this->model()->with($method);

            return $this->addColumn($method, $label)->setRelation(snake_case($method));
        }

        if ($relation instanceof Relations\HasMany
            || $relation instanceof Relations\BelongsToMany
            || $relation instanceof Relations\MorphToMany
        ) {
            $this->model()->with($method);

            return $this->addColumn(snake_case($method), $label);
        }

        return false;
    }

    /**
     * Dynamically add columns to the grid view.
     *
     * @param $method
     * @param $arguments
     *
     * @return Column
     */
    public function __call($method, $arguments)
    {
        $label = isset($arguments[0]) ? $arguments[0] : ucfirst($method);

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
     * Register column displayers.
     *
     * @return void.
     */
    public static function registerColumnDisplayer()
    {
        $map = [
            'editable'    => Displayers\Editable::class,
            'switch'      => Displayers\SwitchDisplay::class,
            'switchGroup' => Displayers\SwitchGroup::class,
            'select'      => Displayers\Select::class,
            'image'       => Displayers\Image::class,
            'label'       => Displayers\Label::class,
            'button'      => Displayers\Button::class,
            'link'        => Displayers\Link::class,
            'badge'       => Displayers\Badge::class,
            'progressBar' => Displayers\ProgressBar::class,
            'radio'       => Displayers\Radio::class,
            'checkbox'    => Displayers\Checkbox::class,
            'orderable'   => Displayers\Orderable::class,
            'table'       => Displayers\Table::class,
        ];

        foreach ($map as $abstract => $class) {
            Column::extend($abstract, $class);
        }
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
    public function setView($view, $variables = [])
    {
        if (!empty($variables)) {
            $this->with($variables);
        }

        $this->view = $view;
    }

    /**
     * Set grid title.
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
     * Set relation for grid.
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
     * Set resource path for grid.
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
     * Get the string contents of the grid view.
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

        return view($this->view, $this->variables())->render();
    }
}
