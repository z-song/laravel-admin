<?php

namespace Encore\Admin\Grid\Filter\Field;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Contracts\Support\Arrayable;

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
     * Set parent filter.
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
        $default = ['' => trans('admin::lang.choose')];

        if (is_string($this->options)) {
            $this->loadAjaxOptions($this->options);

            return $default;
        }

        if ($this->options instanceof \Closure) {
            $this->options = $this->options->bindTo($this->parent);
            $this->options = call_user_func($this->options, $this->parent->getValue());
        }

        if ($this->options instanceof Arrayable) {
            $this->options = $this->options->toArray();
        }

        $options = is_array($this->options) ? $this->options : [];

        return $default + $options;
    }

    /**
     * Load options from ajax.
     *
     * @param string $resourceUrl
     */
    protected function loadAjaxOptions($resourceUrl)
    {
        $script = <<<EOT

$(".{$this->getElementClass()}").select2({
  ajax: {
    url: "$resourceUrl",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term,
        page: params.page
      };
    },
    processResults: function (data, params) {
      params.page = params.page || 1;

      return {
        results: data.data,
        pagination: {
          more: data.next_page_url
        }
      };
    },
    cache: true
  },
  minimumInputLength: 1,
  escapeMarkup: function (markup) {
      return markup;
  }
});

EOT;

        Admin::script($script);
    }

    /**
     * @return array
     */
    public function variables()
    {
        return [
            'options' => $this->buildOptions(),
            'class'   => $this->getElementClass(),
        ];
    }

    /**
     * @return string
     */
    protected function getElementClass()
    {
        return str_replace('.', '_', $this->parent->getColumn());
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
