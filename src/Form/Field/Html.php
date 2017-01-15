<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Html extends Field
{
    /**
     * Htmlable.
     *
     * @var string
     */
    protected $html = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * Create a new Html instance.
     *
     * @param mixed $html
     * @param array $arguments
     */
    public function __construct($html, $arguments)
    {
        $this->html = $html;

        $this->label = array_get($arguments, 0);
    }

    /**
     * Render html field.
     *
     * @return string
     */
    public function render()
    {
        return <<<EOT
<div class="form-group">
    <label  class="col-sm-2 control-label">{$this->label}</label>
    <div class="col-sm-6">
        {$this->html}
    </div>
</div>
EOT;
    }
}
