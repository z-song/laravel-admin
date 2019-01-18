<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Arrayable;

class Checkbox extends AbstractDisplayer
{
    public function display($options = [])
    {
        if ($options instanceof \Closure) {
            $options = $options->call($this, $this->row);
        }

        $radios = '';
        $name = $this->column->getName();

        $key = $this->row->{$this->grid->getKeyName()};

        if (is_string($this->value)) {
            $this->value = explode(',', $this->value);
        }

        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        foreach ($options as $value => $label) {
            $checked = in_array($value, $this->value) ? 'checked' : '';
            $radios .= <<<EOT
<div class="checkbox checkbox-{$name}">
    <label>
        <input type="checkbox" name="grid-checkbox-{$name}-{$key}" value="{$value}" $checked data-key="$key" />{$label}
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

        $element = ".checkbox-$name input:checkbox";

        return <<<EOT
$("$element").iCheck({checkboxClass:'icheckbox_minimal-blue'})
.on('ifChanged', function(event){
    var pk = $(this).data('key');
    var checkBoxArr = [];
    $("input:checkbox[name='grid-checkbox-{$name}-"+pk+"']:checked").each(function() {
        checkBoxArr.push($(this).val());
    });
    $.ajax({
        url: "{$this->getResource()}/" + pk,
        type: "POST",
        data: {
            $name: checkBoxArr,
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
