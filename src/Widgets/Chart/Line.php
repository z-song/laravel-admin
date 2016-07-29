<?php

namespace Encore\Admin\Widgets\Chart;

class Line extends Chart
{
    protected $colorNames = [
        'aqua'    => [0, 255, 255],
        'black'   => [0, 0, 0],
        'blue'    => [0, 0, 255],
        'fuchsia' => [255, 0, 255],
        'gray'    => [128, 128, 128],
        'green'   => [0, 128, 0],
        'lime'    => [0, 255, 0],
        'maroon'  => [128, 0, 0],
        'navy'    => [0, 0, 128],
        'olive'   => [128, 128, 0],
        'purple'  => [128, 0, 128],
        'red'     => [255, 0, 0],
        'silver'  => [192, 192, 192],
        'teal'    => [0, 128, 128],
        'white'   => [255, 255, 255],
        'yellow'  => [255, 255, 0],
    ];

    public function script()
    {
        $options = json_encode($this->options);

        return <<<EOT

(function(){
    var data = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                label: "My First dataset",
                fillColor: "rgba(0,255,0,0.2)",
                //strokeColor: "rgba(220,220,220,1)",
                //pointColor: "rgba(220,220,220,1)",
                //pointStrokeColor: "#fff",
                //pointHighlightFill: "#fff",
                //pointHighlightStroke: "rgba(220,220,220,1)",
                data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
                label: "My Second dataset",
                fillColor: "rgba(151,187,205,0.2)",
                //strokeColor: "rgba(151,187,205,1)",
                //pointColor: "rgba(151,187,205,1)",
                //pointStrokeColor: "#fff",
                //pointHighlightFill: "#fff",
                //pointHighlightStroke: "rgba(151,187,205,1)",
                data: [28, 48, 40, 19, 86, 27, 90]
            },
            {
                label: "My Third dataset",
                fillColor: "rgba(255,255,0,0.2)",
                //strokeColor: "rgba(151,187,205,1)",
                //pointColor: "rgba(151,187,205,1)",
                //pointStrokeColor: "#fff",
                //pointHighlightFill: "#fff",
                //pointHighlightStroke: "rgba(151,187,205,1)",
                data: [56, 78, 12, 34, 35, 67, 90]
            }
        ]
    };

    var canvas = $("#{$this->elementId}").get(0).getContext("2d");
    var chart = new Chart(canvas).Line(data, $options);
})();
EOT;
    }
}
