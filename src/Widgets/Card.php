<?php

namespace Encore\Admin\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;

class Card extends Widget implements Renderable
{
    /**
     * @var string
     */
    protected $view = 'admin::widgets.card';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $content = 'here is the card content.';

    /**
     * @var string
     */
    protected $footer = '';

    /**
     * @var array
     */
    protected $tools = [];

    /**
     * @var string
     */
    protected $script;

    /**
     * Box constructor.
     *
     * @param string $title
     * @param string $content
     */
    public function __construct($title = '', $content = '', $footer = '')
    {
        if ($title) {
            $this->title($title);
        }

        if ($content) {
            $this->content($content);
        }

        if ($footer) {
            $this->footer($footer);
        }

        $this->class('card card-outline');
    }

    /**
     * @param string $title
     * @param string $content
     * @param string $footer
     *
     * @return static
     */
    public static function create($title = '', $content = '', $footer = '')
    {
        return new static(...func_get_args());
    }

    /**
     * Set box content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        if ($content instanceof Renderable) {
            $this->content = $content->render();
        } elseif ($content instanceof Htmlable) {
            $this->content = $content->toHtml();
        } elseif ($content instanceof Jsonable) {
            $this->content = '<pre>'.$content->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
        } elseif (is_array($content)) {
            $this->content = '<pre>'.json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
        } else {
            $this->content = (string) $content;
        }

        return $this;
    }

    /**
     * Set box footer.
     *
     * @param string $footer
     *
     * @return $this
     */
    public function footer($footer)
    {
        if ($footer instanceof Renderable) {
            $this->footer = $footer->render();
        } else {
            $this->footer = (string) $footer;
        }

        return $this;
    }

    /**
     * Set box title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set box as collapsable.
     *
     * @return $this
     */
    public function collapsable()
    {
        $this->tools[] =
            '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>';

        return $this;
    }

    public function maximizable()
    {
        $this->tools[] =
            '<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </button>';

        return $this;
    }

    /**
     * Set box as removable.
     *
     * @return $this
     */
    public function removable()
    {
        $this->tools[] =
            '<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>';

        return $this;
    }

    /**
     *  Set box body scrollable.
     *
     * @param array $options
     *
     * @return $this
     */
    public function scrollable($options = [], $nodeSelector = '')
    {
        $this->id = uniqid('box-slim-scroll-');
        $scrollOptions = json_encode($options);
        $nodeSelector = $nodeSelector ?: '.card-body';

        $this->script = <<<SCRIPT
$("#{$this->id} {$nodeSelector}").slimScroll({$scrollOptions});
SCRIPT;

        return $this;
    }

    /**
     * Set box style.
     *
     * @param string $styles
     *
     * @return $this|Box
     */
    public function style($styles)
    {
        if (is_string($styles)) {
            return $this->style([$styles]);
        }

        $styles = array_map(function ($style) {
            return 'card-'.$style;
        }, $styles);

        $this->class = $this->class.' '.implode(' ', $styles);

        return $this;
    }

    /**
     * Add `box-solid` class to box.
     *
     * @return $this
     */
    public function solid()
    {
        return $this->style('solid');
    }

    /**
     * Variables in view.
     *
     * @return array
     */
    protected function variables()
    {
        return [
            'title'      => $this->title,
            'content'    => $this->content,
            'footer'     => $this->footer,
            'tools'      => $this->tools,
            'attributes' => $this->formatAttributes(),
            'script'     => $this->script,
        ];
    }

    /**
     * Render box.
     *
     * @return string
     */
    public function render()
    {
        return view($this->view, $this->variables())->render();
    }
}
