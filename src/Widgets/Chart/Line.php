<?php

namespace Encore\Admin\Widgets\Chart;

class Line extends Chart
{
    public function script()
    {
        $data = json_encode($this->data);

        $options = json_encode($this->options);

        return <<<EOT

(function(){
    var data = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                label: "My First dataset",
                fillColor: "rgba(220,220,220,0.2)",
                //strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                //pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                //pointHighlightStroke: "rgba(220,220,220,1)",
                data: [65, 59, 80, 81, 56, 55, 40]
            },
//            {
//                label: "My Second dataset",
//                fillColor: "rgba(151,187,205,0.2)",
//                //strokeColor: "rgba(151,187,205,1)",
//                pointColor: "rgba(151,187,205,1)",
//                //pointStrokeColor: "#fff",
//                pointHighlightFill: "#fff",
//                //pointHighlightStroke: "rgba(151,187,205,1)",
//                data: [28, 48, 40, 19, 86, 27, 90]
//            },
//            {
//                label: "My Third dataset",
//                fillColor: "rgba(151,187,205,0.2)",
//                //strokeColor: "rgba(151,187,205,1)",
//                pointColor: "rgba(151,187,205,1)",
//                //pointStrokeColor: "#fff",
//                pointHighlightFill: "#fff",
//                //pointHighlightStroke: "rgba(151,187,205,1)",
//                data: [56, 78, 12, 34, 35, 67, 90]
//            }
        ]
    };

    var canvas = $("#{$this->elementId}").get(0).getContext("2d");
    var chart = new Chart(canvas).Line(data, $options);
})();
EOT;

    }
}
