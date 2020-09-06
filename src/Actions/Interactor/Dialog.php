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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function confirm($title, $text = '', $options = [])
    {
        return $this->addOptions($title, 'question', $text, $options);
    }

    /**
     * @param string $title
     * @param string $icon
     * @param string $text
     * @param array  $options
     *
     * @return $this
     */
    protected function addOptions($title, $icon, $text = '', $options = [])
    {
        $this->options = array_merge(compact('title', 'text', 'icon'), $options);

        return $this;
    }

    /**
     * @param array $data
     *
     * @throws \Throwable
     *
     * @return mixed|string
     */
    public function addScript($data = [])
    {
        call_user_func([$this->action, 'dialog']);

        $data['options'] = $this->options;

        return Admin::view('admin::actions.dialog', $data);
    }
}
