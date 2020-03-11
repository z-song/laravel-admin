<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'fa-pencil';

    protected static $css = [
        '/vendor/laravel-admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $css_fa5 = [
        '/vendor/laravel-admin/font-awesome-5/iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    protected static $js_fa5 = [
        '/vendor/laravel-admin/font-awesome-5/iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    public static function getAssets()
    {
        if (config('admin.fontawesome') === 5) {
            return [
                'css' => static::$css_fa5,
                'js'  => static::$js_fa5,
            ];
        } else {
            return [
                'css' => static::$css,
                'js'  => static::$js,
            ];
        }
    }

    public function render()
    {
        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').iconpicker({placement:'bottomLeft'});

EOT;

        $this->prepend('<i class="fa fa-pencil fa-fw"></i>')
            ->defaultAttribute('style', 'width: 140px');

        return parent::render();
    }
}
