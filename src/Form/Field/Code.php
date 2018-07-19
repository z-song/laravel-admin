<?php

namespace Encore\Admin\Form\Field;
use Encore\Admin\Form\Field;

class Code extends Field
{

    protected $view = 'admin::form.code';

    protected static $css = [
        // main css file
        '/vendor/laravel-admin/codemirror/lib/codemirror.css',
        // darcula theme
        '/vendor/laravel-admin/codemirror/theme/darcula.css',
        // full screen
        '/vendor/laravel-admin/codemirror/addon/display/fullscreen.css',
    ];

    protected static $js = [
        // main js file
        '/vendor/laravel-admin/codemirror/lib/codemirror.js',
        // matchbrackets
        '/vendor/laravel-admin/codemirror/addon/edit/matchbrackets.js',
        // active line
        '/vendor/laravel-admin/codemirror/addon/selection/active-line.js',
        // full screen
        '/vendor/laravel-admin/codemirror/addon/display/fullscreen.js',
        // x-httpd-php mode
        '/vendor/laravel-admin/codemirror/mode/htmlmixed/htmlmixed.js',
        '/vendor/laravel-admin/codemirror/mode/xml/xml.js',
        '/vendor/laravel-admin/codemirror/mode/javascript/javascript.js',
        '/vendor/laravel-admin/codemirror/mode/css/css.js',
        '/vendor/laravel-admin/codemirror/mode/clike/clike.js',
        '/vendor/laravel-admin/codemirror/mode/php/php.js',
    ];

    public function render()
    {
        $this->script = <<<EOT
var editor = CodeMirror.fromTextArea($this->id, {
    lineNumbers: true, // show lineNumbers
    indentUnit: 4, // indentUnit 4
    styleActiveLine: true, // styleActiveLine
    matchBrackets: true, // matchBrackets
    mode: 'application/x-httpd-php', // php mode
    lineWrapping: true, // lineWrapping
    theme: 'darcula', // darcula theme
}); 
editor.setOption("extraKeys", {
    // Esc fullScreen
    "Esc": function(cm) {
        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
    }
});
EOT;
        return parent::render();
    }
}
