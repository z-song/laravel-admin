<?php

namespace Encore\Admin\Table\Displayers;

use Encore\Admin\Admin;
use Encore\Admin\Table\Simple;
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

        return route(config('admin.route.as') . 'handle_renderable', compact('renderable'));
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

        $html = '';

        if ($async = is_subclass_of($callback, Renderable::class)) {
            $this->renderable = $callback;
        } else {
            $html = call_user_func_array($callback->bindTo($this->row), [$this->row]);
        }

        $mark = str_replace('.', '_', $this->getColumn()->getName());

        return Admin::view('admin::table.display.modal', [
            'url'      => $this->getLoadUrl(),
            'async'    => $async,
            'table'    => is_subclass_of($callback, Simple::class),
            'title'    => $title,
            'html'     => $html,
            'key'      => $this->getKey(),
            'value'    => $this->value,
            'name'     => $this->getKey().'-'.$mark,
            'mark'     => $mark, 
        ]);
    }
}
