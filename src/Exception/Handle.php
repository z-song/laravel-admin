<?php

namespace Encore\Admin\Exception;

class Handle
{
    protected $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    public function render()
    {
        return view('admin::error', ['e' => $this->exception])->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
