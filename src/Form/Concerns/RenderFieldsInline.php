<?php

namespace Encore\Admin\Form\Concerns;

use Encore\Admin\Form\Field\Inline;

trait RenderFieldsInline
{
    /**
     * @var Inline
     */
    protected $inline;

    /**
     * @param string $label
     */
    public function beginInline($label)
    {
        $this->inline = new Inline($label);
    }

    public function endInline()
    {
        $this->pushField($this->inline);

        $this->inline = null;
    }

    /**
     * @param string $label
     * @param \Closure $callback
     * @return $this
     */
    public function inline(string $label, \Closure $callback)
    {
        $this->beginInline($label);

        call_user_func($callback, $this);

        $this->endInline();

        return $this;
    }
}
