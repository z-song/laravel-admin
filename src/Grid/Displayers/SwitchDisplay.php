<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Column;

class SwitchDisplay extends AbstractDisplayer
{
    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];

    public function __construct($value, Grid $grid, Column $column, $row)
    {
        $modelName = $grid->getModelName();
        $this->states['on']['text'] = admin_translate($modelName, $this->states['on']['text']);
        $this->states['off']['text'] = admin_translate($modelName, $this->states['off']['text']);
        parent::__construct($value, $grid, $column, $row);
    }

    protected function updateStates($states)
    {
        foreach (array_dot($states) as $key => $state) {
            array_set($this->states, $key, $state);
        }
    }

    public function display($states = [])
    {
        $this->updateStates($states);

        $name = $this->column->getName();

        $class = "grid-switch-{$name}";

        $script = <<<EOT

$('.$class').bootstrapSwitch({
    size:'mini',
    onText: '{$this->states['on']['text']}',
    offText: '{$this->states['off']['text']}',
    onColor: '{$this->states['on']['color']}',
    offColor: '{$this->states['off']['color']}',
    onSwitchChange: function(event, state){

        $(this).val(state ? 'on' : 'off');

        var pk = $(this).data('key');
        var value = $(this).val();

        $.ajax({
            url: "{$this->grid->resource()}/" + pk,
            type: "POST",
            data: {
                $name: value,
                _token: LA.token,
                _method: 'PUT'
            },
            success: function (data) {
                toastr.success(data.message);
            }
        });
    }
});

EOT;

        Admin::script($script);

        $key = $this->row->{$this->grid->getKeyName()};

        $checked = $this->states['on']['value'] == $this->value ? 'checked' : '';

        return <<<EOT
        <input type="checkbox" class="$class" $checked data-key="$key" />
EOT;
    }
}
