<?php

namespace Encore\Admin\Grid\Filter\Field;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class Select
{
    /**
     * Options of select.
     *
     * @var array
     */
    protected $options = [];

    /**
     * @var AbstractFilter
     */
    protected $parent;

    /**
     * Select constructor.
     *
     * @param mixed $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Set parent filter
     *
     * @param AbstractFilter $filter
     */
    public function setParent(AbstractFilter $filter)
    {
        $this->parent = $filter;
    }

    /**
     * Build options.
     *
     * @return array
     */
    protected function buildOptions()
    {
        if ($this->options instanceof \Closure) {
            $this->options = call_user_func($this->options, $this->parent->getValue());
        }

        if ($this->options instanceof Arrayable) {
            $this->options = $this->options->toArray();
        }

        $options = is_array($this->options) ? $this->options : [];

        return ['' => trans('admin::lang.choose')] + $options;
    }

    /**
     * @return array
     */
    public function variables()
    {
        return [
            'options' => $this->buildOptions(),
            'class'   => str_replace('.', '_', $this->parent->getColumn())
        ];
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'select';
    }

    /**
     * Load options for other select when change.
     *
     * @param string $target
     * @param string $resourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function load($target, $resourceUrl, $idField = 'id', $textField = 'text')
    {
        $column = $this->parent->getColumn();

        $script = <<<EOT

$(document).on('change', ".{$this->getClass($column)}", function () {
    var target = $(this).closest('form').find(".{$this->getClass($target)}");
    $.get("$resourceUrl?q="+this.value, function (data) {
        target.find("option").remove();
        $.each(data, function (i, item) {
            $(target).append($('<option>', {
                value: item.$idField,
                text : item.$textField
            }));
        });
        
        $(target).trigger('change');
    });
});
EOT;

        Admin::script($script);

        return $this;
    }

    /**
     * Get form element class.
     *
     * @param string $target
     *
     * @return mixed
     */
    protected function getClass($target)
    {
        return str_replace('.', '_', $target);
    }
}
