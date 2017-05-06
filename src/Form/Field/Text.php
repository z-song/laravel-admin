<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Text extends Field
{
    use PlainInput;

    protected $content;

    protected $icon = 'fa-pencil';

    /**
     * To change Text input value
     * @param string $content
     */
    public function content($content = '')
    {
        if (is_callable($content)) {
            $this->content = $content;
        } else {
            $this->content = (string)$content;
        }

        return $this;
    }

    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->initPlainInput();

        if ($this->content instanceof \Closure) {
            if ($this->form) {
                $valueAttribute = call_user_func($this->content, $this->form);
            } else {
                $valueAttribute = call_user_func($this->content, $this->value);
            }
        } else {
            $valueAttribute = old($this->column, $this->value());
        }

        $this->prepend('<i class="fa ' . $this->icon . '"></i>')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', $valueAttribute)
            ->defaultAttribute('class', 'form-control ' . $this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        return parent::render()->with([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);
    }
}
