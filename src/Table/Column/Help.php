<?php

namespace Encore\Admin\Table\Column;

use Illuminate\Contracts\Support\Renderable;

class Help implements Renderable
{
    /**
     * @var string
     */
    protected $message = '';

    /**
     * Help constructor.
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->message = $message;
    }

    /**
     * Render help  header.
     *
     * @return string
     */
    public function render()
    {
        $data = [
            'toggle'    => 'tooltip',
            'placement' => 'bottom',
            'html'      => 'true',
            'title'     => $this->message,
        ];

        $data = collect($data)->map(function ($val, $key) {
            return "data-{$key}=\"{$val}\"";
        })->implode(' ');

        return <<<HELP
<a href="javascript:void(0);" class="table-column-help" {$data}>
    <i class="fa fa-question-circle"></i>
</a>
HELP;
    }
}
