<?php

namespace Encore\Admin\Widgets\Chart;

class PolarArea extends Pie
{
    public function script()
    {
        $data = $this->fillColor($this->data);

        $data = json_encode($data);

        $options = json_encode($this->options);

        return <<<EOT
(function(){

    var canvas = $("#{$this->elementId}").get(0).getContext("2d");
    var chart = new Chart(canvas).PolarArea($data, $options);

})();

EOT;

    }
}
