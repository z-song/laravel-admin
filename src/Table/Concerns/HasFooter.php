<?php

namespace Encore\Admin\Table\Concerns;

use Closure;
use Encore\Admin\Table\Tools\Footer;

trait HasFooter
{
    /**
     * @var Closure
     */
    protected $footer;

    /**
     * Set table footer.
     *
     * @param Closure|null $closure
     *
     * @return $this|Closure
     */
    public function footer(Closure $closure = null)
    {
        if (!$closure) {
            return $this->footer;
        }

        $this->footer = $closure;

        return $this;
    }

    /**
     * Render table footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (!$this->footer) {
            return '';
        }

        return (new Footer($this))->render();
    }
}
