<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

class SwitchDisplay extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'style' => ''],
        'off' => ['value' => 0, 'text' => 'OFF', 'style' => 'default'],
    ];

    protected function overrideStates($states)
    {
        if (empty($states)) {
            return;
        }

        foreach (Arr::dot($states) as $key => $state) {
            Arr::set($this->states, $key, $state);
        }

        $this->states['on']['style'] = $this->states['on']['style'] ?: admin_color();
    }

    public function display($states = [])
    {
        $this->overrideStates($states);

        // @see vendor/encore/laravel-admin/resources/views/table/inline-edit/switch.blade.php
        return Admin::view('admin::table.inline-edit.switch', [
            'class'    => 'table-switch-'.str_replace('.', '-', $this->getName()),
            'key'      => $this->getKey(),
            'resource' => $this->getResource(),
            'name'     => $this->getPayloadName(),
            'states'   => $this->states,
            'checked'  => $this->states['on']['value'] == $this->getValue() ? 'checked' : '',
            'options'  => [
                'size'     => 'xs',
                'width'    => 60,
                'on'       => $this->states['on']['text'],
                'off'      => $this->states['off']['text'],
                'onstyle'  => $this->states['on']['style'],
                'offstyle' => $this->states['off']['style'],
            ],
        ]);
    }
}
