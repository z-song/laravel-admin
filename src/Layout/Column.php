<?php

namespace Encore\Admin\Layout;

use Illuminate\Contracts\Support\Renderable;

class Column implements Buildable
{
    /**
     * @var int
     */
    protected $width = 12;

    /**
     * @var array
     */
    protected $contents = [];

    /**
     * Column constructor.
     * @param $content
     * @param int $width
     */
    public function __construct($content, $width = 12)
    {
        if ($content instanceof \Closure) {
            call_user_func($content, $this);
        } else {
            $this->append($content);
        }

        $this->width = $width;
    }

    /**
     * Append content to column.
     *
     * @param $content
     *
     * @return $this
     */
    public function append($content)
    {
        $this->contents[] = $content;

        return $this;
    }

    /**
     * Add a row for column.
     *
     * @param $content
     *
     * @return Column
     */
    public function row($content)
    {
        $row = new Row();

        call_user_func($content, $row);

        ob_start();

        $row->build();

        $contents = ob_get_contents();

        ob_end_clean();

        return $this->append($contents);
    }

    /**
     * Build column html.
     */
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

    /**
     * Start column.
     */
    protected function startColumn()
    {
        echo "<div class=\"col-md-{$this->width}\">";
    }

    /**
     * End column.
     */
    protected function endColumn()
    {
        echo '</div>';
    }
}
