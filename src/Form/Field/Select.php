<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;

class Select extends Field
{
    protected static $css = [
        '/packages/admin/AdminLTE/plugins/select2/select2.min.css',
    ];

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/select2/select2.full.min.js',
    ];

    public function render()
    {
        if (empty($this->script)) {
            $this->script = "$(\".{$this->getElementClass()}\").select2({allowClear: true});";
        }

        if (is_callable($this->options)) {
            $options = call_user_func($this->options, $this->value);
            $this->options($options);
        }

        $this->options = array_filter($this->options);

        return parent::render()->with(['options' => $this->options]);
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
        // remote options
        if (is_string($options)) {
            return call_user_func_array([$this, 'loadOptionsFromRemote'], func_get_args());
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        if (is_callable($options)) {
            $this->options = $options;
        } else {
            $this->options = (array) $options;
        }

        return $this;
    }

    /**
     * Load options for other select on change.
     *
     * @param $field
     * @param $source
     */
    public function load($field, $source)
    {
        $script = <<<EOT

$(".{$this->getElementClass()}").change(function () {
    $.get("$source?q="+this.value, function (data) {
        $("#$field option").remove();
        $("#$field").select2({data: data});
    });
});
EOT;

        Admin::script($script);
    }

    /**
     * Load options from remote.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $options
     *
     * @return $this
     */
    protected function loadOptionsFromRemote($url, $parameters = [], $options = [])
    {
        $ajaxOptions = [
            'url' => $url.'?'.http_build_query($parameters),
        ];

        $ajaxOptions = json_encode(array_merge($ajaxOptions, $options));

        $this->script = <<<EOT

$.ajax($ajaxOptions).done(function(data) {
  $(".{$this->getElementClass()}").select2({data: data});
});

EOT;

        return $this;
    }

    /**
     * Load options from ajax results.
     *
     * @param string $url
     *
     * @return $this
     */
    public function ajax($url)
    {
        $this->script = <<<EOT

$(".{$this->getElementClass()}").select2({
  ajax: {
    url: "$url",
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

        return $this;
    }
}
