<?php

namespace Encore\Admin\Widgets\Form;

use Illuminate\Contracts\Support;

trait HasResponse
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @return Response
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
     * @param mixed $data
     *
     * @return $this
     */
    public function dump($data)
    {
        return $this->result(admin_dump($data));
    }

    /**
     * @param mixed $result
     *
     * @return $this
     */
    public function result($result)
    {
        if ($result instanceof Support\Arrayable) {
            $result = $result->toArray();
        } elseif ($result instanceof Support\Renderable) {
            $result = $result->render();
        } elseif ($result instanceof Support\Htmlable) {
            $result = $result->toHtml();
        }

        if (is_array($result)) {
            $result = json_encode($result, JSON_PRETTY_PRINT);
            $result = "<pre><code>{$result}</code></pre>";
        }

        $this->response->result = $result;

        return $this;
    }
}
