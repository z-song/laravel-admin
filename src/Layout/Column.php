<?php
/**
 * Created by PhpStorm.
 * User: encore
 * Date: 16/4/5
 * Time: 下午3:57
 */

namespace Encore\Admin\Layout;


class Column implements Buildable
{
    protected $width = 12;

    protected $contents = [];

    public function __construct($width, $content)
    {
        $this->width = $width;

        if ($content instanceof \Closure) {
            call_user_func($content, $this);
        } else {
            $this->append($content);
        }
    }

    public function append($content)
    {
        $this->contents[] = $content;
    }

    public function build()
    {
        foreach ($this->contents as $content) {

        }

        return $this->wrapper('hi');
    }

    public function wrapper($content)
    {
        return "<div class=\"col-md-{$this->width}\">$content</div>";
    }
}

