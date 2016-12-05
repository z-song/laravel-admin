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
     * Create a new Html instance.
     *
     * @param mixed $html
     */
    public function __construct($html)
    {
        $this->html = $html;
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
    <label  class="col-sm-2 control-label"></label>
    <div class="col-sm-6">
        $this->html
    </div>
</div>
EOT;

    }
}
