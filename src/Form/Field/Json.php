<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Json extends Field
{
    protected $js = [
        'codemirror/lib/codemirror.js',
        'codemirror/mode/javascript/javascript.js',
        'codemirror/addon/edit/matchbrackets.js',
    ];

    protected $css = [
        'codemirror/lib/codemirror.css',
    ];

    public function render()
    {
        $this->script = <<<EOT

var editor = CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
    lineNumbers: true,
    mode: "application/ld+json",
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
});

EOT;
        return parent::render();
    }
}