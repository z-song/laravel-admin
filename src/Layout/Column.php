<?php

namespace Encore\Admin\Layout;

use Illuminate\Contracts\Support\Renderable;

class Column implements Buildable
{
    protected $width = 12;

    protected $contents = [];

    public function __construct($content, $width = 12)
    {
        if ($content instanceof \Closure) {
            call_user_func($content, $this);
        } else {
            $this->append($content);
        }

        $this->width = $width;
    }

    public function append($content)
    {
        $this->contents[] = $content;
    }

    public function build()
    {
        $this->startColumn();

        foreach ($this->contents as $content) {
            if ($content instanceof Renderable) {
                echo $content->render();
            } else {
                echo (string) $content;
            }
        }

        $this->endColumn();
    }


    protected function startColumn()
    {
        echo "<div class=\"col-md-{$this->width}\">";
    }

    protected function endColumn()
    {
        echo "</div>";
    }
}
