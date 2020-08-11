<?php

namespace Encore\Admin\Table\Concerns;

use Encore\Admin\Table;
use Encore\Admin\Table\Exporter;
use Encore\Admin\Table\Exporters\AbstractExporter;

trait CanExportTable
{
    /**
     * Export driver.
     *
     * @var string
     */
    protected $exporter;

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

        $this->disablePagination();

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
     * Set exporter driver for Table to export.
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
        $input = array_merge(request()->all(), Exporter::formatExportQuery($scope, $args));

        if ($constraints = $this->model()->getConstraints()) {
            $input = array_merge($input, $constraints);
        }

        return $this->resource().'?'.http_build_query($input);
    }

    /**
     * If table show export btn.
     *
     * @return bool
     */
    public function showExportBtn()
    {
        return $this->option('show_exporter');
    }

    /**
     * Disable export.
     *
     * @return $this
     */
    public function disableExport(bool $disable = true)
    {
        return $this->option('show_exporter', !$disable);
    }

    /**
     * Render export button.
     *
     * @return string
     */
    public function renderExportButton()
    {
        return (new Table\Tools\ExportButton($this))->render();
    }

    /**
     * @param \Closure $callback
     */
    public function export(\Closure $callback)
    {
        if (!$scope = request(Exporter::$queryName)) {
            return;
        }

        $this->getExporter($scope)->setCallback($callback);

        return $this;
    }
}
