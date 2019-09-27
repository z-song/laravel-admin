<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Combination extends Field
{
    /**
     * @var string
     */
    protected static $css = '/vendor/laravel-admin/bootstrap-combination/bootstrap-combination.css';

    /**
     * @var array
     */
    protected static $js = [
        '/vendor/laravel-admin/bootstrap-combination/bootstrap-combination.js',
    ];

    /**
     * @var string
     */
    protected $views = 'admin::form.combination';

    /**
     * @var string
     */
    protected $attribute_class = '';

    /**
     * @var array
     */
    protected $grid = [];

    /**
     * Combination constructor.
     *
     * @param $attributeClass
     */
    public function __construct($attributeClass)
    {
        $this->attribute_class = $attributeClass;
    }

    /**
     * Get the view variables of this field.
     *
     * @return array
     */
    public function variables()
    {
        return array_merge($this->variables, [
            'viewClass'       => $this->getViewElementClasses(),
            'attribute_class' => $this->attribute_class,
            'grid'            => $this->grid,
            'dataValue'       => $this->value(),
        ]);
    }

    /**
     * combination grid.
     *
     * @param array $grid
     *
     * @return $this
     */
    public function grid($grid)
    {
        $this->grid = json_encode($grid);

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->script = <<<EOT
        $('#bootstrap-combination').bootstrapCombinations();
EOT;

        return parent::render();
    }
}
