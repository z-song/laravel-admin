<?php

namespace Encore\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;

class Checkbox extends MultipleSelect
{
    protected $inline = true;

    protected $hasCheckAll = false;

    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

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
            $this->options = (array)$options;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function hasCheckAll()
    {
        $this->hasCheckAll = true;

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

        $this->checked = (array)$checked;

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
        $this->script = "$('{$this->getElementClassSelector()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});";

        $this->addVariables([
            'checked'     => $this->checked,
            'inline'      => $this->inline,
            'hasCheckAll' => $this->hasCheckAll,
        ]);

        if ($this->hasCheckAll) {

            $checkAllClass = uniqid('check-all-');

            $this->script .= <<<SCRIPT
$('.{$checkAllClass}').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {
    if (this.checked) {
        $('{$this->getElementClassSelector()}').iCheck('check');
    } else {
        $('{$this->getElementClassSelector()}').iCheck('uncheck');
    }
})
SCRIPT;
            $this->addVariables(['checkAllClass' => $checkAllClass]);
        }

        return parent::render();
    }
}
