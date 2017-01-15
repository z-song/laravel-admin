<?php

namespace Encore\Admin\Field\DataField;

use Illuminate\Contracts\Support\Arrayable;

class Checkbox extends MultipleSelect
{
    protected static $css = [
        '/packages/admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        'packages/admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public function prepare(array $value)
    {
        return $value;
    }

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

        $this->options = (array) $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->script = "$('.{$this->getElementClass()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});";

        return parent::render();
    }
}
