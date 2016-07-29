<?php

namespace Encore\Admin\Grid\Filter;

use Encore\Admin\Admin;

class Between extends AbstractFilter
{
    protected $view = null;

    /**
     * Format id.
     *
     * @param string $column
     *
     * @return array|string
     */
    public function formatId($column)
    {
        $id = str_replace('.', '_', $column);

        return ['start' => "{$id}_start", 'end' => "{$id}_end"];
    }

    /**
     * Format two field names of this filter.
     *
     * @param string $column
     *
     * @return array
     */
    protected function formatName($column)
    {
        $columns = explode('.', $column);

        if (count($columns) == 1) {
            $name = $columns[0];
        } else {
            $name = array_shift($columns);

            foreach ($columns as $column) {
                $name .= "[$column]";
            }
        }

        return ['start' => "{$name}[start]", 'end' => "{$name}[end]"];
    }

    public function condition($inputs)
    {
        if (!array_has($inputs, $this->column)) {
            return;
        }

        $this->value = array_get($inputs, $this->column);

        $value = array_filter($this->value, function ($val) {
            return $val !== '';
        });

        if (empty($value)) {
            return;
        }

        if (!isset($value['start'])) {
            return $this->buildCondition($this->column, '<=', $value['end']);
        }

        if (!isset($value['end'])) {
            return $this->buildCondition($this->column, '>=', $value['start']);
        }

        $this->query = 'whereBetween';

        return $this->buildCondition($this->column, $this->value);
    }

    public function datetime()
    {
        $this->view = 'admin::filter.betweenDatetime';

        $this->prepareForDatetime();
    }

    protected function prepareForDatetime()
    {
        $options['format'] = 'YYYY-MM-DD HH:mm:ss';
        $options['locale'] = config('app.locale');

        $startOptions = json_encode($options);
        $endOptions = json_encode($options + ['useCurrent' => false]);

        $script = <<<EOT
            $('#{$this->id['start']}').datetimepicker($startOptions);
            $('#{$this->id['end']}').datetimepicker($endOptions);
            $("#{$this->id['start']}").on("dp.change", function (e) {
                $('#{$this->id['end']}').data("DateTimePicker").minDate(e.date);
            });
            $("#{$this->id['end']}").on("dp.change", function (e) {
                $('#{$this->id['start']}').data("DateTimePicker").maxDate(e.date);
            });
EOT;

        Admin::script($script);
    }

    public function render()
    {
        if (isset($this->view)) {
            return view($this->view, $this->variables());
        }

        return parent::render();
    }
}
