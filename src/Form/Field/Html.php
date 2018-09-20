<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Html extends Field
{
    /**
     * Htmlable.
     *
     * @var string|\Closure
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
     * Fill in the data.
     */
    public function fill($data)
    {
        $this->value = $data;
    }

    /**
     * Render html field.
     *
     * @return string
     */
    public function render()
    {
        if ($this->html instanceof \Closure) {
            $callback = $this->html->bindTo($this->form->model());

            $this->html = call_user_func($callback, $this->form, $this->value);
        }

        return <<<EOT
<div class="form-group row">
    <label  class="col-sm-{$this->width['label']} control-label">{$this->label}</label>
    <div class="col-sm-{$this->width['field']}">
        {$this->html}
    </div>
</div>
EOT;
    }
}
