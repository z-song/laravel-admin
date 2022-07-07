<?php

namespace Encore\Admin\Grid\Concerns;

use Closure;
use Encore\Admin\Grid\Tools\Header;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;

trait HasHeader
{
    /**
     * @var array
     */
    protected $header;

    /**
     * Set grid header.
     *
     * @param Closure|null $closure
     *
     * @return $this|Closure
     */
    public function header(Closure $closure = null)
    {
        if (!$closure) {
            return function ($query) {
                return array_reduce($this->header, function ($contents, $closure) use ($query) {
                    $content = call_user_func($closure, $query);
                    if ($content instanceof Renderable) {
                        $content = $content->render();
                    }

                    if ($content instanceof Htmlable) {
                        $content = $content->toHtml();
                    }

                    return $contents . $content;
                });
            };
        }

        $this->header[] = $closure;

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
