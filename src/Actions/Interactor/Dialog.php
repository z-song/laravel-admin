<?php

namespace Encore\Admin\Actions\Interactor;

use Encore\Admin\Admin;

class Dialog extends Interactor
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function success($title, $text = '', $options = [])
    {
        return $this->addOptions($title, __FUNCTION__, $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function error($title, $text = '', $options = [])
    {
        return $this->addOptions($title, __FUNCTION__, $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return $this
     */
    public function warning($title, $text = '', $options = [])
    {
        return $this->addOptions($title, __FUNCTION__, $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function info($title, $text = '', $options = [])
    {
        return $this->addOptions($title, __FUNCTION__, $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function question($title, $text = '', $options = [])
    {
        return $this->addOptions($title, __FUNCTION__, $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function confirm($title, $text = '', $options = [])
    {
        return $this->addOptions($title, 'question', $text, $options);
    }

    /**
     * @param string $title
     * @param string $type
     * @param string $text
     * @param array  $options
     *
     * @return $this
     */
    protected function addOptions($title, $type, $text = '', $options = [])
    {
        $this->options = array_merge(compact('title', 'text', 'type'), $options);

        return $this;
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Throwable
     */
    public function addScript($data = [])
    {
        call_user_func([$this->action, 'dialog']);

        $data['options'] = $this->options;

        return Admin::view('admin::actions.dialog', $data);
    }
}
