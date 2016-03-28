<?php

namespace Encore\Admin\Chart;

use Encore\Admin\Facades\Admin;

class Line
{
    protected $js = [
        'AdminLTE/plugins/chartjs/Chart.min.js'
    ];

    public function __construct($data, $options)
    {
        $this->data = $data;

        $this->options = $options;
    }

    /**
     * @return string
     */
    public function render()
    {
        $data = json_encode($this->data);
        $options = json_encode($this->options);

        $script = <<<SCRIPT

        var data = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86, 27, 90]
        }
    ]
};

    var ctx = $("#chart").get(0).getContext("2d");
    var myNewChart = new Chart(ctx).Line(data);

SCRIPT;

        Admin::script($script);

        Admin::js($this->js);

        return view('admin::chart')->render();
    }
}
