<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Alert extends Widget implements Renderable
{
    /**
     * @var string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var string
     */
    protected $style = 'danger';

    /**
     * @var string
     */
    protected $icon = 'ban';

    /**
     * Alert constructor.
     *
     * @param mixed  $content
     * @param string $title
     * @param string $style
     */
    public function __construct($content, $title = '', $style = 'danger')
    {
        $this->content = (string) $content;

        $this->title = $title ?: trans('admin::lang.alert');

        $this->style = $style;
    }

    /**
     * Add style.
     *
     * @param string $style
     * @return $this
     */
    public function style($style = 'info')
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Add icon.
     *
     * @param string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return array
     */
    protected function variables()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'style'   => $this->style,
            'icon'    => $this->icon,
        ];
    }

    /**
     * Render alter.
     *
     * @return string
     */
    public function render()
    {
        return view('admin::widgets.alert', $this->variables())->render();
    }
}
