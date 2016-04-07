<?php

namespace Encore\Admin\Widgets\Chart;

class PolarArea extends Pie
{
    public function script()
    {
        $this->data = $this->fillColor($this->data);

        $data = json_encode($this->data);

        $options = json_encode($this->options);

        return <<<EOT
(function(){

    var canvas = $("#{$this->elementId}").get(0).getContext("2d");
    var chart = new Chart(canvas).PolarArea($data, $options);

})();

EOT;

    }
}
