<?php

namespace Encore\Admin\Widgets\Form;

use Encore\Admin\Widgets\Table;
use Illuminate\Contracts\Support;
use Illuminate\Support\Fluent;

trait HasResponse
{
    /**
     * @var Fluent
     */
    protected $response;

    /**
     * @return Fluent
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Respond with a success message.
     *
     * @param string $message
     *
     * @return $this
     */
    public function success($message = '')
    {
        $this->response->status = true;
        $this->response->message = $message;

        return $this;
    }

    /**
     * Respond with a error message.
     *
     * @param string $message
     *
     * @return $this
     */
    public function error($message = '')
    {
        $this->response->status = false;
        $this->response->message = $message;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function redirect($url)
    {
        $this->response->redirect = $url;

        return $this;
    }

    /**
     * @return $this
     */
    public function refresh()
    {
        $this->response->refresh = true;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function download($url)
    {
        $this->response->download = $url;

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function dump($data)
    {
        return $this->result(admin_dump($data));
    }

    /**
     * @return $this
     */
    public function table()
    {
        // call  table method in form method.
        if (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] !== 'handle') {
            return $this->__call('table', func_get_args());
        }

        if (func_num_args() == 1) {
            $table = new Table([], func_get_args()[0]);
        } else {
            $table = new Table(...func_get_args());
        }

        return $this->result($table);
    }

    /**
     * @param mixed $result
     *
     * @return $this
     */
    public function result($result)
    {
        if ($result instanceof Support\Renderable) {
            $result = $result->render();
        } elseif ($result instanceof Support\Htmlable) {
            $result = $result->toHtml();
        } elseif ($result instanceof Support\Arrayable) {
            $result = $result->toArray();
        }

        if (is_array($result)) {
            $result = json_encode($result, JSON_PRETTY_PRINT);
            $result = "<pre><code>{$result}</code></pre>";
        }

        $this->response->result = $result;

        return $this;
    }
}
