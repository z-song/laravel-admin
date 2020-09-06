<?php

namespace Encore\Admin\Table\Concerns;

use Closure;
use Encore\Admin\Table\Tools\Header;

trait HasHeader
{
    /**
     * @var Closure
     */
    protected $header;

    /**
     * Set table header.
     *
     * @param Closure|null $closure
     *
     * @return $this|Closure
     */
    public function header(Closure $closure = null)
    {
        if (!$closure) {
            return $this->header;
        }

        $this->header = $closure;

        return $this;
    }

    /**
     * @return string
     */
    public function renderHeader()
    {
        if (!$this->header) {
            return '';
        }

        return (new Header($this))->render();
    }
}
