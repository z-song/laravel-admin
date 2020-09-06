<?php

namespace Encore\Admin\Table\Exporters;

use Encore\Admin\Table;

abstract class AbstractExporter implements ExporterInterface
{
    /**
     * @var \Encore\Admin\Table
     */
    protected $table;

    /**
     * @var int
     */
    protected $page;

    /**
     * Create a new exporter instance.
     *
     * @param $table
     */
    public function __construct(Table $table = null)
    {
        if ($table) {
            $this->setTable($table);
        }
    }

    /**
     * Set table for exporter.
     *
     * @param Table $table
     *
     * @return $this
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table of table.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table->model()->getOriginalModel()->getTable();
    }

    /**
     * Get data with export query.
     *
     * @param bool $toArray
     *
     * @return array|\Illuminate\Support\Collection|mixed
     */
    public function getData($toArray = true)
    {
        return $this->table->getFilter()->execute($toArray);
    }

    /**
     * @param callable $callback
     * @param int      $count
     *
     * @return bool
     */
    public function chunk(callable $callback, $count = 100)
    {
        $this->table->applyQuery();

        return $this->table->getFilter()->chunk($callback, $count);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        return collect($this->getData());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getQuery()
    {
        $model = $this->table->getFilter()->getModel();

        $queryBuilder = $model->getQueryBuilder();

        // Export data of giving page number.
        if ($this->page) {
            $keyName = $this->table->getKeyName();
            $perPage = request($model->getPerPageName(), $model->getPerPage());

            $scope = (clone $queryBuilder)
                ->select([$keyName])
                ->setEagerLoads([])
                ->forPage($this->page, $perPage)->get();
            // If $querybuilder is a Model, it must be reassigned, unless it is a eloquent/query builder.
            $queryBuilder = $queryBuilder->whereIn($keyName, $scope->pluck($keyName));
        }

        return $queryBuilder;
    }

    /**
     * Export data with scope.
     *
     * @param string $scope
     *
     * @return $this
     */
    public function withScope($scope)
    {
        if ($scope == Table\Exporter::SCOPE_ALL) {
            return $this;
        }

        list($scope, $args) = explode(':', $scope);

        if ($scope == Table\Exporter::SCOPE_CURRENT_PAGE) {
            $this->table->model()->usePaginate(true);
            $this->page = $args ?: 1;
        }

        if ($scope == Table\Exporter::SCOPE_SELECTED_ROWS) {
            $selected = explode(',', $args);
            $this->table->model()->whereIn($this->table->getKeyName(), $selected);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function export();
}
