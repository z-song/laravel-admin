<?php

namespace Encore\Admin\Layout;

use Closure;
use Illuminate\Contracts\Support\Renderable;

class Content implements Renderable
{
    protected $header = '';

    protected $description = '';

    /**
     * @var Row[]
     */
    protected $rows = [];

    public function __construct(\Closure $callback)
    {
        $callback($this);
    }

    public function header($header = '')
    {
        $this->header = $header;
    }

    public function description($description = '')
    {
        $this->description = $description;
    }

    public function row($content)
    {
        if ($content instanceof Closure) {
            $row = new Row();
            call_user_func($content, $row);
            $this->addRow($row);
        } else {
            $this->addRow(new Row($content));
        }

        return $this;
    }

    public function body($content)
    {
        return $this->row($content);
    }

    protected function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    public function build()
    {
        ob_start();

        foreach ($this->rows as $row) {
            $row->build();
        }

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }

    /**
     * @return string
     */
    public function render()
    {
        $items = [
            'header' => $this->header,
            'description' => $this->description,
            'content' => $this->build()
        ];

        return view('admin::content', $items)->render();
    }

    public function __toString()
    {
        return $this->render();
    }
}
