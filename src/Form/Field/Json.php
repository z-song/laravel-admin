<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Json extends Field
{
    protected static $css = [
        '/packages/admin/codemirror/lib/codemirror.css',
    ];

    protected static $js = [
        '/packages/admin/codemirror/lib/codemirror.js',
        '/packages/admin/codemirror/mode/javascript/javascript.js',
        '/packages/admin/codemirror/addon/edit/matchbrackets.js',
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
