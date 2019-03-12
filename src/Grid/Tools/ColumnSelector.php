<?php

namespace Encore\Admin\Grid\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Illuminate\Support\Collection;

class ColumnSelector extends AbstractTool
{
    const SELECT_COLUMN_NAME = '_columns_';

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new Export button instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function render()
    {
        $show = array_filter(explode(',', request(static::SELECT_COLUMN_NAME)));

        $columns = $this->getGridColumns();

        $this->setupScript($show, $columns);

        $lists = $columns->map(function ($val, $key) use ($show) {
            if (empty($show)) {
                $checked = 'checked';
            } else {
                $checked = in_array($key, $show) ? 'checked' : '';
            }

            return "<li><a href=\"#\" data-value=\"{$key}\" tabIndex=\"-1\"><input type=\"checkbox\" {$checked}/>&nbsp;&nbsp;&nbsp;{$val}</a></li>";
        })->implode("\r\n");

        return <<<EOT

<div class="dropdown pull-right column-selector" style="margin-right: 10px">
    <button type="button" class="btn btn-sm btn-instagram dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-table"></i>
        &nbsp;
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        {$lists}
        <li class="divider"></li>
        <li style="padding: 0 15px;">
            <button class="btn btn-xs btn-instagram column-select-all">全选</button>
            <button class="btn btn-xs btn-primary column-select-submit" style="float: right">确定</button>
        </li>
    </ul>
</div>
EOT;
    }

    /**
     * @return Collection
     */
    protected function getGridColumns()
    {
        return $this->grid->columns()->map(function (Grid\Column $column) {
            $name = $column->getName();

            if (in_array($name, ['__row_selector__', '__actions__'])) {
                return;
            }

            return [$name => $column->getLabel()];
        })->filter()->collapse();
    }

    /**
     * @param $show
     * @param $columns
     */
    protected function setupScript($show, $columns)
    {
        if (empty($show)) {
            $show = $columns->keys()->toArray();
        }

        $show = json_encode($show);

        $script = <<<SCRIPT

var selected_columns = {$show};

$('.column-selector .dropdown-menu a').on('click', function(event) {

   var \$target = $( event.currentTarget ),
       val = \$target.attr('data-value'),
       \$inp = \$target.find('input'),
       idx;
       
   if ((idx = selected_columns.indexOf(val)) > -1) {
      selected_columns.splice(idx, 1);
      setTimeout(function() {\$inp.prop('checked', false)}, 0);
   } else {
      selected_columns.push(val);
      setTimeout(function() {\$inp.prop('checked', true)}, 0);
   }

   $(event.target).blur();
   
   return false;
});

$('.column-select-submit').on('click', function () {
    if (selected_columns.length == 0) {
        return;
    }

    var url = new URL(location);
    
    // select all
    if ($('.column-selector .dropdown-menu a input').length == selected_columns.length) {
        url.searchParams.delete('_columns_');
    } else {
        url.searchParams.set('_columns_', selected_columns.join());
    }

    $.pjax({container:'#pjax-container', url: url.toString() });
});

$('.column-select-all').on('click', function () {
    selected_columns = [];
    $('.column-selector .dropdown-menu a input').prop('checked', true);
    $('.column-selector .dropdown-menu a').each(function (_, val) {
        selected_columns.push($(val).data('value'));
    });
    
    return false;
});

SCRIPT;

        Admin::script($script);
    }
}
