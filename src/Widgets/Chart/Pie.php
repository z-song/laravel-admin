<?php

namespace Encore\Admin\Widgets\Chart;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

class Pie extends Chart
{
    public function __construct($data = [])
    {
        $this->add($data);
    }

    public function add($label, $value = '', $color = '', $highlight = '')
    {
        if (is_array($label)) {
            if (Arr::isAssoc($label)) {
                $this->data[] = $label;
            } else {
                foreach ($label as $item) {
                    call_user_func_array([$this, 'add'], $item);
                }
            }

            return $this;
        }

        $this->data[] = [
            'label'     => $label,
            'value'     => $value,
            'color'     => $color,
            'highlight' => $highlight,
        ];

        return $this;
    }

    protected $defaultColors = [
        '#dd4b39', '#00a65a', '#f39c12',
        '#00c0ef', '#3c8dbc', '#0073b7',
        '#39cccc', '#ff851b', '#01ff70',
        '#605ca8', '#f012be', '#777',
        '#001f3f', '#d2d6de',
    ];

    protected function fillColor($data)
    {
        foreach ($data as &$item) {
            if (empty($item['color'])) {
                $item['color'] = $item['highlight'] = array_shift($this->defaultColors);
            }
        }

        return $data;
    }

    public function script()
    {
        $this->data = $this->fillColor($this->data);

        $data = json_encode($this->data);

        $options = json_encode($this->options);

        return <<<EOT
(function(){

    var canvas = $("#{$this->elementId}").get(0).getContext("2d");
//    var chart = new Chart(canvas).Pie($data, $options);
    var chart = new Chart(canvas,{type:'pie',data:$data,options:$options});
})();
EOT;
    }

    public function render()
    {
        $this->elementId = $this->makeElementId();

        Admin::script($this->script());

        return view('admin::widgets.chart.pie', ['id' => $this->elementId, 'data' => $this->data])->render();
    }
}
