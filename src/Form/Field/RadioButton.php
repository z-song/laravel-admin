<?php

namespace Encore\Admin\Form\Field;

class RadioButton extends Radio
{
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
