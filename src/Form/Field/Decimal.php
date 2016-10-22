<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Decimal extends Field
{
    public function render()
    {
        $this->script = "$('#{$this->id}').inputmask('decimal', {
    rightAlign: true
  });";

        return parent::render();
    }
}
