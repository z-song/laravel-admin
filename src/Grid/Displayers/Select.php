<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Select extends AbstractDisplayer
{
    protected function addScript()
    {
        $name = $this->column->getName();

        $class = "grid-select-{$name}";

        $script = <<<EOT

$('.$class .dropdown-menu li a').click(function () {
    var target = $(this);

    var text = target.text();
    var value = target.data('value');
    var key = target.parents('.$class').attr('key');

    $.ajax({
        url: "{$this->getResource()}/" + key,
        type: "POST",
        data: {
            $name: value,
            _token: LA.token,
            _method: 'PUT'
        },
        success: function (data) {
            target.parents('.$class').find('.select-text').text(text);

            target.parents('.dropdown-menu').find('li a').each(function () {
                if (value == $(this).data('value')) {
                    $(this).find('i').removeClass('invisible')
                } else {
                    $(this).find('i').addClass('invisible')
                }
            });

            toastr.success(data.message);
        }
    });
});

EOT;

        Admin::script($script);
    }

    protected function addStyle()
    {
        $style = <<<STYLE
.grid-select-{$this->column->getName()} .dropdown-menu {
    min-width: 0px;
}
STYLE;

        Admin::style($style);
    }

    public function display($options = [])
    {
        $this->addScript();
        $this->addStyle();

        $name = $this->column->getName();

        if ($options instanceof \Closure) {
            $options = $options->call($this, $this->row);
        }

        $optionsHtml = '';

        foreach ($options as $option => $text) {
            $invisible = $option == $this->value ? '' : 'invisible';
            $optionsHtml .= "<li><a href='javascript:void(0);' data-value='{$option}'><i class=\"fa fa-check text-green $invisible\"></i>{$text}</a></li>";
        }

        return <<<HTML
<div class="dropdown grid-select-{$this->column->getName()}" key="{$this->getKey()}">
  <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
    <span class="select-text text-muted">{$options[$this->value]}</span>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    {$optionsHtml}
  </ul>
</div>
HTML;
EOT;
    }
}
