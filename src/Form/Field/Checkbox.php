<?php

namespace Encore\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;

class Checkbox extends MultipleSelect
{
    /**
     * @var bool
     */
    protected $inline = true;

    /**
     * @var bool
     */
    protected $canCheckAll = false;

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        if (is_callable($options)) {
            $this->options = $options;
        } else {
            $this->options = (array) $options;
        }

        return $this;
    }

    /**
     * Add a checkbox above this component, so you can select all checkboxes by click on it.
     *
     * @return $this
     */
    public function canCheckAll()
    {
        $this->canCheckAll = true;

        return $this;
    }

    /**
     * Set checked.
     *
     * @param array|callable|string $checked
     *
     * @return $this
     */
    public function checked($checked = [])
    {
        if ($checked instanceof Arrayable) {
            $checked = $checked->toArray();
        }

        $this->checked = (array) $checked;

        return $this;
    }

    /**
     * Draw inline checkboxes.
     *
     * @return $this
     */
    public function inline()
    {
        $this->inline = true;

        return $this;
    }

    /**
     * Draw stacked checkboxes.
     *
     * @return $this
     */
    public function stacked()
    {
        $this->inline = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addVariables([
            'checked'       => $this->checked,
            'inline'        => $this->inline,
            'canCheckAll'   => $this->canCheckAll,
            'checkAllClass' => uniqid('check-all-'),
            'options'       => $this->getOptions(),
        ]);

        $this->addCascadeScript();

        return parent::fieldRender();
    }
}
