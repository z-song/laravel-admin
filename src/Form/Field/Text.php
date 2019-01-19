<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Text extends Field
{
    use PlainInput;

    /**
     * @var string
     */
    protected $icon = 'fa-pencil';

    /**
     * Add custom fa-icon.
     *
     * @param string $icon
     *
     * @return $this
     */
    public function addIcon($icon)
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

        $this->prepend('<i class="fa '.$this->icon.' fa-fw"></i>')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->column, $this->value()))
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());

        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);

        return parent::render();
    }
}
