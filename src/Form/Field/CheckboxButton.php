<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;

class CheckboxButton extends Checkbox
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addCascadeScript();

        $this->addVariables([
            'options' => $this->options,
            'checked' => $this->checked,
        ]);

        return parent::fieldRender();
    }
}
