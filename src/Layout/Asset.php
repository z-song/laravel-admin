<?php

namespace Encore\Admin\Layout;

class Asset
{
    protected $src;

    protected $in_pjax;

    /**
     * @param string $src
     */
    public function __construct(string $src, bool $in_pjax = false)
    {
        $this->src = $src;
        $this->in_pjax = $in_pjax;
    }

    /**
     * Is in pjax.
     *
     * @return bool
     */
    public function inPjax()
    {
        return $this->in_pjax;
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->src;
    }
}
