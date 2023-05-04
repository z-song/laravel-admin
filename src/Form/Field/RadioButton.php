<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Arrayable;

class RadioButton extends Radio
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * Icons for options of specify elements.
     *
     * @var array
     */
    protected $icons = [];

    /**
     * Set icons for options.
     *
     * @param array|callable|string $icons
     *
     * @return $this
     */
    public function icons($icons = [])
    {
        if ($icons instanceof Arrayable) {
            $icons = $icons->toArray();
        }

        $this->icons = (array) $icons;

        return $this;
    }

    protected function addScript()
    {
        $script = <<<'SCRIPT'
$('.radio-group-toggle label').click(function() {
    $(this).parent().children().removeClass('active');
    $(this).addClass('active');
});
SCRIPT;

        Admin::script($script);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addScript();

        $this->addCascadeScript();

        $this->addVariables([
            'icons'   => $this->icons,
            'options' => $this->options,
            'checked' => $this->checked,
        ]);

        return parent::fieldRender();
    }
}
