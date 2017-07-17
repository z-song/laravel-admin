<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

/**
 * Class ListBox
 * @package Encore\Admin\Form\Field
 *
 * @see https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox
 */
class ListBox extends MultipleSelect
{
    protected $settings = [];

    protected static $css = [
        '/vendor/laravel-admin/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js',
    ];

    public function settings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function render()
    {
        $settings = array_merge($this->settings, [
            'infoTextEmpty'     => '空列表',
            'infoText'          => '总共 {0} 项',
            'infoTextFiltered'  => '{0} / {1}',
            'filterTextClear'   => '显示全部',
            'filterPlaceHolder' => '过滤',
        ]);

        $settings = json_encode($settings);

        $this->script = <<<SCRIPT

$("{$this->getElementClassSelector()}").bootstrapDualListbox($settings);

SCRIPT;

        return parent::render();
    }
}