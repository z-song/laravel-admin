<?php

namespace Encore\Admin\Widgets\Form\Fields;

class Mobile extends AbstractField
{
    protected $format = '';

    public function format($format = '999 9999 9999')
    {
        $this->format = $format;
    }

    public function render()
    {
        if (empty($this->format)) {
            $this->format();
        }

        $options = json_encode(['mask' => $this->format]);

        $this->script = <<<EOT

$('#{$this->id()}').inputmask($options);
EOT;

        return parent::render();
    }
}
