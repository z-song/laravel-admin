<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

class SwitchGroup extends SwitchDisplay
{
    public function display($columns = [], $states = [])
    {
        admin_assets('bootstrapSwitch');

        $this->overrideStates($states);

        if (!Arr::isAssoc($columns)) {
            $columns = collect($columns)->map(function ($column) {
                return [$column => ucfirst($column)];
            })->collapse();
        }

        $html = [];

        foreach ($columns as $column => $label) {
            $html[] = $this->buildSwitch($column, $label);
        }

        return '<table>'.implode('', $html).'</table>';
    }

    protected function buildSwitch($name, $label = '')
    {
        return Admin::view('admin::table.inline-edit.switch-group', [
            'class'    => 'table-switch-' . str_replace('.', '-', $name),
            'key'      => $this->getKey(),
            'resource' => $this->getResource(),
            'name'     => $this->getPayloadName($name),
            'states'   => $this->states,
            'checked'  => $this->states['on']['value'] == $this->getAttribute($name) ? 'checked' : '',
            'label'    => $label,
        ]);
    }
}
