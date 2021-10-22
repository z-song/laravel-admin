<?php

namespace Encore\Admin\Grid\Exporters;

use Encore\Admin\Grid\Column;

class CsvExporter extends AbstractExporter
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var array
     */
    protected $exceptColumns;

    /**
     * @var array
     */
    protected $onlyColumns;

    /**
     * @var []\Closure
     */
    protected $columnCallbacks;

    /**
     * @var []\Closure
     */
    protected $titleCallbacks;

    /**
     * @var array
     */
    protected $visibleColumns;

    /**
     * @var array
     */
    protected $columnUseOriginalValue;

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function filename(string $filename = ''): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param \Closure $closure
     */
    public function setCallback(\Closure $closure): self
    {
        $this->callback = $closure;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function except(array $columns = []): self
    {
        $this->exceptColumns = $columns;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function only(array $columns = []): self
    {
        $this->onlyColumns = $columns;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function originalValue($columns = []): self
    {
        $this->columnUseOriginalValue = $columns;

        return $this;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     *
     * @return $this
     */
    public function column(string $name, \Closure $callback): self
    {
        $this->columnCallbacks[$name] = $callback;

        return $this;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     *
     * @return $this
     */
    public function title(string $name, \Closure $callback): self
    {
        $this->titleCallbacks[$name] = $callback;

        return $this;
    }

    /**
     * Get download response headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        if (!$this->filename) {
            $this->filename = $this->getTable();
        }

        return [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment;filename=\"{$this->filename}.csv\"",
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        if ($this->callback) {
            call_user_func($this->callback, $this);
        }

        $response = function () {
            $handle = fopen('php://output', 'w');
            $titles = [];
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF)); //导出的CSV文件是无BOM编码UTF-8，而我们通常使用UTF-8编码格式都是有BOM的。所以添加BOM于CSV中
            $this->chunk(function ($collection) use ($handle, &$titles) {
                Column::setOriginalGridModels($collection);

                $original = $current = $collection->toArray();

                $this->grid->getColumns()->map(function (Column $column) use (&$current) {
                    $current = $column->fill($current);
                    $this->grid->columnNames[] = $column->getName();
                });
                
                // Write title
                if (empty($titles)) {
                    fputcsv($handle, $titles = $this->getVisiableTitles());
                }

                // Write rows
                foreach ($current as $index => $record) {
                    fputcsv($handle, $this->getVisiableFields($record, $original[$index]));
                }
            });
            fclose($handle);
        };

        response()->stream($response, 200, $this->getHeaders())->send();

        exit;
    }

    /**
     * @return array
     */
    protected function getVisiableTitles()
    {
        $titles = $this->grid->visibleColumns()
            ->mapWithKeys(function (Column $column) {
                $columnName = $column->getName();
                $columnTitle = $column->getLabel();
                if (isset($this->titleCallbacks[$columnName])) {
                    $columnTitle = $this->titleCallbacks[$columnName]($columnTitle);
                }

                return [$columnName => $columnTitle];
            });

        if ($this->onlyColumns) {
            $titles = $titles->only($this->onlyColumns);
        }

        if ($this->exceptColumns) {
            $titles = $titles->except($this->exceptColumns);
        }

        $this->visibleColumns = $titles->keys();

        return $titles->values()->toArray();
    }

    /**
     * @param array $value
     * @param array $original
     *
     * @return array
     */
    public function getVisiableFields(array $value, array $original): array
    {
        $fields = [];

        foreach ($this->visibleColumns as $column) {
            $fields[] = $this->getColumnValue(
                $column,
                data_get($value, $column),
                data_get($original, $column)
            );
        }

        return $fields;
    }

    /**
     * @param string $column
     * @param mixed  $value
     * @param mixed  $original
     *
     * @return mixed
     */
    protected function getColumnValue(string $column, $value, $original)
    {
        if (!empty($this->columnUseOriginalValue)
            && in_array($column, $this->columnUseOriginalValue)) {
            return $original;
        }

        if (isset($this->columnCallbacks[$column])) {
            return $this->columnCallbacks[$column]($value, $original);
        }

        return $value;
    }
}
