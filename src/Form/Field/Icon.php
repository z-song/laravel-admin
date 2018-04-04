<?php

namespace Encore\Admin\Form\Field;

class Icon extends Text
{
    protected $default = 'fa-pencil';

    protected static $css = [
        '/vendor/laravel-admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    public function __construct($column, array $arguments = [])
    {
        parent::__construct($column, $arguments);
        $this->options(['placement' => 'bottomLeft']);

    }

    /**
     * Set placement setting of iconpicker.
     * second word must be upper case
     * example bottomLeft or bottomRight
     *
     * @param string $placement
     *
     * @return $this
     */
    public function placement($placement)
    {
        $this->options(['placement' => $placement]);
        return $this;
    }

    public function render()
    {

        $startOptions = json_encode($this->options);

        $this->script = <<<EOT

$('{$this->getElementClassSelector()}').iconpicker($startOptions);

EOT;

        $this->prepend('<i class="fa fa-pencil"></i>')
            ->defaultAttribute('style', 'width: 140px');

        return parent::render();
    }
}
