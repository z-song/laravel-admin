<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Radio extends AbstractDisplayer
{
    public function display($options = [])
    {
        if ($options instanceof \Closure) {
            $options = $options->call($this, $this->row);
        }

        $radios = '';
        $name = $this->column->getName();

        $key = $this->row->{$this->grid->getKeyName()};

        foreach ($options as $value => $label) {
            $checked = ($value == $this->value) ? 'checked' : '';
            $radios .= <<<EOT
<div class="radio radio-$name">
    <label>
        <input type="radio" name="grid-radio-$name-$key" value="{$value}" $checked data-key="$key" />{$label}
    </label>
</div>
EOT;
        }
        Admin::script($this->script());

        return $radios;
    }

    protected function script()
    {
        $name = $this->column->getName();

        $key = $this->row->{$this->grid->getKeyName()};

        $element = ".radio-$name input:radio";

        return <<<EOT
$("$element").iCheck({radioClass:'iradio_minimal-blue'})
.on('ifChecked', function(event){
    var pk = $(this).data('key');
    var value = $(this).val();
    $.ajax({
        url: "{$this->getResource()}/" + pk,
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
    return false;
});
EOT;
    }
}
