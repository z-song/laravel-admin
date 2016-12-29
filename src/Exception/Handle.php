<?php

namespace Encore\Admin\Exception;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class Handle
{
    protected $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    public function render()
    {
        $error = new MessageBag([
            'type'      => get_class($this->exception),
            'message'   => $this->exception->getMessage(),
            'file'      => $this->exception->getFile(),
            'line'      => $this->exception->getLine(),
        ]);

        $errors = new ViewErrorBag();
        $errors->put('_exception_', $error);

        return view('admin::partials.error', compact('errors'))->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
