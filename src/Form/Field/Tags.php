<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class Tags extends Field
{
    protected $value = [];
    protected $direction = "ltr";

    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css',
    ];

    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js',
    ];

    public function fill($data)
    {
        $this->value = array_get($data, $this->column);

        if (is_string($this->value)) {
            $this->value = explode(',', $this->value);
        }

        $this->value = array_filter((array)$this->value);
    }

    /**
     * Set direction setting of select2.
     *
     */
    public function dir($dir = 'ltr')
    {
        $this->direction = $dir;

        return $this;
    }

    public function prepare($value)
    {
        if (is_array($value) && !Arr::isAssoc($value)) {
            $value = implode(',', array_filter($value));
        }

        return $value;
    }

    /**
     * Load options from ajax results.
     *
     * @param string $url
     * @param        $idField
     * @param        $textField
     *
     * @return $this
     */
    public function ajax($url, $idField = 'id', $textField = 'text')
    {
        $this->script = <<<EOT

$("{$this->getElementClassSelector()}").select2({
  dir: "$this->direction",
  language : "$this->local",
  tags: true,
  multiple: true,
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
        results: $.map(data.data, function (d) {
                   d.id = d.$idField;
                   d.text = d.$textField;
                   return d;
                }),
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

    public function render()
    {
        if (empty($this->script)) {
            $this->script = "$(\"{$this->getElementClassSelector()}\").select2({
            dir: \"$this->direction\",
            language : \"$this->local\",
            tags: true,
            tokenSeparators: [',']
        });";

        }

        return parent::render();
    }
}
