<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Code extends Field
{
    protected $js = [
        'codemirror/lib/codemirror.js',
        'codemirror/mode/clike/clike.js',
    ];

    protected $css = [
        'codemirror/lib/codemirror.css',
    ];

    protected $mode = '';

    public function lang($lang = 'php')
    {
        $this->mode = "text/x-$lang";

        $this->js[] = "codemirror/mode/$lang/$lang.js";
    }

    public function render()
    {
        if (empty($this->mode)) {
            $this->lang();
        }

        $this->script = <<<EOT

var editor = CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
    lineNumbers: true,
    mode: "{$this->mode}",
    extraKeys: {
        "Tab": function(cm){
            cm.replaceSelection("    " , "end");
        }
     }
});

EOT;
        return parent::render();
    }
}
