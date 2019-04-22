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
        if (!$this->grid->showColumnSelector()) {
            return '';
        }
        $show = array_filter(explode(',', request(static::SELECT_COLUMN_NAME)));

        $columns = $this->getGridColumns();

        $this->setupScript();

        $lists = $columns->map(function ($val, $key) use ($show) {
            if (empty($show)) {
                $checked = 'checked';
            } else {
                $checked = in_array($key, $show) ? 'checked' : '';
            }

            return <<<HTML
<li class="checkbox icheck" style="margin: 0;">
    <label style="width: 100%;padding: 3px;">
        <input type="checkbox" class="column-select-item" value="{$key}" {$checked}/>&nbsp;&nbsp;&nbsp;{$val}
    </label>
</li>
HTML;

        })->implode("\r\n");

        $btns = [
            'all'    => __('admin.all'),
            'submit' => __('admin.submit'),
        ];

        return <<<EOT

<div class="dropdown pull-right column-selector" style="margin-right: 10px">
    <button type="button" class="btn btn-sm btn-instagram dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-table"></i>
        &nbsp;
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;">
        <li>
            <ul style='padding: 0;'>
                {$lists}
            </ul>
        </li>
        <li class="divider"></li>
        <li class="text-right">
            <button class="btn btn-sm btn-defalut column-select-all">{$btns['all']}</button>&nbsp;&nbsp;
            <button class="btn btn-sm btn-primary column-select-submit">{$btns['submit']}</button>
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

    protected function setupScript()
    {
        $script = <<<SCRIPT

$('.column-select-submit').on('click', function () {
    
    var selected = [];
    
    $('.column-select-item:checked').each(function () {
        selected.push($(this).val());
    });

    if (selected.length == 0) {
        return;
    }

    var url = new URL(location);
    
    // select all
    if ($('.column-select-item').length == selected.length) {
        url.searchParams.delete('_columns_');
    } else {
        url.searchParams.set('_columns_', selected.join());
    }

    $.pjax({container:'#pjax-container', url: url.toString()});
});

$('.column-select-all').on('click', function () {
    $('.column-select-item').iCheck('check');
    return false;
});

$('.column-select-item').iCheck({
    checkboxClass:'icheckbox_minimal-blue'
});

SCRIPT;

        Admin::script($script);
    }
}
