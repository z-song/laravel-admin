<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Map extends Field
{
    protected $js = [
        'http://map.qq.com/api/js?v=2.exp'
    ];

    public function __construct($column, $arguments)
    {
        $this->column['lat']  = $column;
        $this->column['lng']  = $arguments[0];

        array_shift($arguments);

        $this->label  = $this->formatLabel($arguments);
        $this->id     = $this->formatId($this->column);
    }

    public function render()
    {
        $this->script =  <<<EOT
            var lat = $('#{$this->id['lat']}').val();
            var lng = $('#{$this->id['lng']}').val();

            var center = new qq.maps.LatLng(lat, lng);

            var container = document.getElementById("container");
            var map = new qq.maps.Map(container, {
                center: center,
                zoom: 13
            });

            var marker = new qq.maps.Marker({
                position: center,
                draggable: true,
                map: map
            });

            if( ! lat || ! lng) {
                var citylocation = new qq.maps.CityService({
                    complete : function(result){
                        map.setCenter(result.detail.latLng);
                        marker.setPosition(result.detail.latLng);
                    }
                });

                citylocation.searchLocalCity();
            }

            qq.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
            });

            qq.maps.event.addListener(marker, 'position_changed', function(event) {
                var position = marker.getPosition();
                $('#{$this->id['lat']}').val(position.getLat());
                $('#{$this->id['lng']}').val(position.getLng());
            });
EOT;

        return parent::render();
    }
}