<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Json extends AbstractField
{
    public function render()
    {
        $this->script = <<<EOT

var editor = CodeMirror.fromTextArea(document.getElementById("{$this->id()}"), {
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
