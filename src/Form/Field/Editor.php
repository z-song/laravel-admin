<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

/**
 * add class 'ckeditor' for each ckeditor if you want jquery validation
 * Class Editor
 * @package Encore\Admin\Form\Field
 */
class Editor extends Field
{
    protected static $js = [
        '/js/plugins/ckeditor/ckeditor.js',
//        '//cdn.ckeditor.com/4.5.10/standard/ckeditor.js',
    ];

    public function __construct($column, array $arguments = [])
    {
        parent::__construct($column, $arguments);

        $this->options(['contentsLangDirection' => 'ltr']);
        $this->options(['language' => config('app.locale', 'en')]);
        //for jquery validation
        $this->addElementClass(['validate','ckeditor']);

    }

    /**
     * set `direction` .
     * https://docs-old.ckeditor.com/ckeditor_api/symbols/CKEDITOR.config.html#.contentsLangDirection
     * 'ui' – indicates that content direction will be the same as the user interface language direction;
     * 'ltr' – for Left-To-Right language (like English);
     * 'rtl' – for Right-To-Left languages (like Arabic).
     * @param string $dir
     * @return $this
     */
    public function direction($dir ='ltr')
    {
         $this->options(['contentsLangDirection' => $dir]);
        $this->direction = $dir;

        return $this;    }

    /**
     * set language for editor
     * @param string $dir
     * @return $this
     */
    public function lang($dir)
    {
        return $this->options(['language' => $dir]);
    }

    public function render()
    {
        $options = json_encode($this->options);
        $this->script = "CKEDITOR.replace('{$this->column}', $options);";

        return parent::render();
    }
}
