<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Html extends DataField
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
