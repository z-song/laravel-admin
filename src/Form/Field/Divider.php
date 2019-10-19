<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Divider extends Field
{
    protected $title;
    protected $class = 'divider';

    public function __construct($title = '')
    {
        $this->title = $title;
    }

    public function render()
    {
        if (empty($this->title)) {
            return '<hr>';
        }

        return <<<HTML
<div class="{$this->class}">
  <span class="$this->class-title">
    {$this->title}
  </span>
</div>
HTML;
    }

    /**
     * Add note style for divider.
     *
     * @return $this
     */
    public function note()
    {
        $this->class = 'note';

        return $this;
    }
}
