<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class Tags extends Field
{
    /**
     * explode and prepare data - database and form
     * @var string
     */
    public $TokenSeparator = ',';

    /**
     * @var array
     */
    protected $value = [];

    /**
     * @var array
     */
    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css',
    ];

    /**
     * @var array
     */
    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js',
    ];

    /**
     * set Token Separator default is ","
     * @param string $value
     * @return $this
     */
    public function setTokenSeparator($value = ",")
    {
        $this->TokenSeparator = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fill($data)
    {
        $this->value = array_get($data, $this->column);

        if (is_string($this->value)) {
            $this->value = explode($this->TokenSeparator, $this->value);
        }

        $this->value = array_filter((array) $this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        if (is_array($value) && !Arr::isAssoc($value)) {
            $value = implode($this->TokenSeparator, array_filter($value));
        }

        return $value;
    }

    /**
     * Get or set value for this field.
     *
     * @param mixed $value
     *
     * @return $this|array|mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return empty($this->value) ? ($this->getDefault() ?? []) : $this->value;
        }

        $this->value = $value;

        return $this;
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
    
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->script = "$(\"{$this->getElementClassSelector()}\").select2({
            tags: true,
            tokenSeparators: [\"$this->TokenSeparator\"],
            dir: \"$this->direction\",
            language : \"$this->local\",
        });";

        return parent::render();
    }
}