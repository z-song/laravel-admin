<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Markdown extends Field
{
    protected $js = [
        //'bootstrap-markdown/js/markdown.js',
        'bootstrap-markdown/js/bootstrap-markdown.js'
    ];

    protected $css = [
        'bootstrap-markdown/css/bootstrap-markdown.min.css'
    ];
}
