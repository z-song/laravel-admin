<?php

namespace Encore\Admin\Field\DataField;

use Encore\Admin\Field\DataField;

class Button extends DataField
{
    protected $class = 'btn-primary';

    public function info()
    {
        $this->class = 'btn-info';

        return $this;
    }

    public function on($event, $callback)
    {
        $this->script = <<<EOT

        $('.{$this->getElementClass()}').on('$event', function() {
            $callback
        });

EOT;
    }
}
