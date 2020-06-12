<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class Textarea extends Field
{
    use HasValuePicker;

    /**
     * Default rows of textarea.
     *
     * @var int
     */
    protected $rows = 5;

    /**
     * @var string
     */
    protected $append = '';

    /**
     * Set rows of textarea.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function rows($rows = 5)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        if (is_array($this->value)) {
            $this->value = json_encode($this->value, JSON_PRETTY_PRINT);
        }

        $this->mountPicker(function ($btn) {
            $this->addPickBtn($btn);
        });

        return parent::render()->with([
            'append' => $this->append,
            'rows'   => $this->rows
        ]);
    }

    /**
     * @param string $wrap
     */
    protected function addPickBtn($btn)
    {
        $style = <<<STYLE
.textarea-picker {
    padding: 5px;
    border-bottom: 1px solid #d2d6de;
    border-left: 1px solid #d2d6de;
    border-right: 1px solid #d2d6de;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    background-color: #f1f2f3;
}

.textarea-picker .btn {
    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
}
STYLE;
        Admin::style($style);

        $this->append = <<<HTML
<div class="text-right textarea-picker">
    {$btn}
</div>
HTML;
        return $this;
    }
}
