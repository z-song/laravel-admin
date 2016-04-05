<?php

namespace Encore\Admin\Layout;

use Illuminate\Contracts\Support\Renderable;

class Row implements Buildable
{
    protected $columns = [];

    public function __construct($content = '')
    {
        if (! empty($content)) {
            $this->addColumn($content);
        }
    }

    public function column($width, $content)
    {
        $column = new Column($width, $content);

        $this->addColumn($column);
    }

    protected function addColumn($column)
    {
        $this->columns[] = $column;
    }

    public function build()
    {
        $this->startRow();

        foreach ($this->columns as $column) {

            if ($column instanceof Column) {
                $column->build();
            } elseif ($column instanceof Renderable) {
                echo $column->render();
            } else {
                echo (string) $column;
            }
        }

        $this->endRow();
    }

    protected function startRow()
    {
        echo "<div class=\"row\">";
    }

    protected function endRow()
    {
        echo "</div>";
    }
}
