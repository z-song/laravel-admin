<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Radio extends Field
{
    protected static $css = [
        '/packages/admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        'packages/admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

    protected $values;

    public function render()
    {
        $this->options['radioClass'] = 'iradio_minimal-blue';

        $this->script = "$('.{$this->id}').iCheck(".json_encode($this->options).');';

        return parent::render()->with(['values' => $this->values]);
    }

    public function values($values)
    {
        $this->values = $values;

        return $this;
    }
}
