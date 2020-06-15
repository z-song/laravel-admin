<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Simple;
use Illuminate\Contracts\Support\Renderable;

class Modal extends AbstractDisplayer
{
    /**
     * @var string
     */
    protected $renderable;

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $renderable = str_replace('\\', '_', $this->renderable);

        return route('admin.handle-renderable', compact('renderable'));
    }

    /**
     * @param \Closure|string $callback
     *
     * @return mixed|string
     */
    public function display($callback = null)
    {
        if (func_num_args() == 2) {
            list($title, $callback) = func_get_args();
        } elseif (func_num_args() == 1) {
            $title = $this->trans('title');
        }

        $html  = '';

        if ($async = is_subclass_of($callback, Renderable::class)) {
            $this->renderable = $callback;
        } else {
            $html = call_user_func_array($callback->bindTo($this->row), [$this->row]);
        }

        return Admin::component('admin::components.column-modal', [
            'url'     => $this->getLoadUrl(),
            'async'   => $async,
            'grid'    => is_subclass_of($callback, Simple::class),
            'title'   => $title,
            'html'    => $html,
            'key'     => $this->getKey(),
            'value'   => $this->value,
            'name'    => $this->getKey() . '-' . str_replace('.', '_', $this->getColumn()->getName()),
        ]);
    }
}
