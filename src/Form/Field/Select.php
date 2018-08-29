<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class Select extends Field
{
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
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $config = [];

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
            // reload selected
            if (class_exists($options) && in_array('Illuminate\Database\Eloquent\Model', class_parents($options))) {
                return $this->model(...func_get_args());
            }

            return $this->loadRemoteOptions(...func_get_args());
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
     * @param array $groups
     */

    /**
     * Set option groups.
     *
     * eg: $group = [
     *        [
     *        'label' => 'xxxx',
     *        'options' => [
     *            1 => 'foo',
     *            2 => 'bar',
     *            ...
     *        ],
     *        ...
     *     ]
     *
     * @param array $groups
     *
     * @return $this
     */
    public function groups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Load options for other select on change.
     *
     * @param string $field
     * @param string $sourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function load($field, $sourceUrl, $idField = 'id', $textField = 'text')
    {
        if (Str::contains($field, '.')) {
            $field = $this->formatName($field);
            $class = str_replace(['[', ']'], '_', $field);
        } else {
            $class = $field;
        }

        $script = <<<EOT
$(document).off('change', "{$this->getElementClassSelector()}");
$(document).on('change', "{$this->getElementClassSelector()}", function () {
    var target = $(this).closest('.fields-group').find(".$class");
    $.get("$sourceUrl?q="+this.value, function (data) {
        target.find("option").remove();
        $(target).select2({
            data: $.map(data, function (d) {
                d.id = d.$idField;
                d.text = d.$textField;
                return d;
            })
        }).trigger('change');
    });
});
EOT;

        Admin::script($script);

        return $this;
    }

    /**
     * Load options for other selects on change.
     *
     * @param string $fields
     * @param string $sourceUrls
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function loads($fields = [], $sourceUrls = [], $idField = 'id', $textField = 'text')
    {
        $fieldsStr = implode('.', $fields);
        $urlsStr = implode('^', $sourceUrls);
        $script = <<<EOT
var fields = '$fieldsStr'.split('.');
var urls = '$urlsStr'.split('^');

var refreshOptions = function(url, target) {
    $.get(url).then(function(data) {
        target.find("option").remove();
        $(target).select2({
            data: $.map(data, function (d) {
                d.id = d.$idField;
                d.text = d.$textField;
                return d;
            })
        }).trigger('change');
    });
};

$(document).off('change', "{$this->getElementClassSelector()}");
$(document).on('change', "{$this->getElementClassSelector()}", function () {
    var _this = this;
    var promises = [];

    fields.forEach(function(field, index){
        var target = $(_this).closest('.fields-group').find('.' + fields[index]);
        promises.push(refreshOptions(urls[index] + "?q="+ _this.value, target));
    });

    $.when(promises).then(function() {
        console.log('开始更新其它select的选择options');
    });
});
EOT;

        Admin::script($script);

        return $this;
    }

    /**
     * Load options from current selected resource(s).
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string                             $idField
     * @param string                             $textField
     *
     * @return $this
     */
    public function model($model, $idField = 'id', $textField = 'name')
    {
        $this->options = function ($resource) use ($model, $idField, $textField) {
            if (null == $resource) {
                return [];
            }

            if (is_array($resource) && !empty($resource) && isset($resource[0]['id'])) {
                $resource = array_map(function ($res) {
                    return $res['id'];
                }, $resource);
            } elseif (is_array($resource) && !empty($resource) && isset($resource['id'])) {
                $resource = $resource['id'];
            }

            $model = $model::find($resource);

            if ($model) {
                if ($model instanceof \Illuminate\Support\Collection) {
                    $results = [];

                    foreach ($model as $result) {
                        $results[$result->{$idField}] = $result->{$textField};
                    }

                    return $results;
                }

                return [$model->{$idField} => $model->{$textField}];
            }

            return [];
        };

        return $this;
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
    protected function loadRemoteOptions($url, $parameters = [], $options = [])
    {
        $ajaxOptions = [
            'url' => $url.'?'.http_build_query($parameters),
        ];

        $ajaxOptions = json_encode(array_merge($ajaxOptions, $options));

        $this->script = <<<EOT

$.ajax($ajaxOptions).done(function(data) {

  var select = $("{$this->getElementClassSelector()}");

  select.select2({data: data});
  
  var value = select.data('value') + '';
  
  if (value) {
    value = value.split(',');
    select.select2('val', value);
  }
});

EOT;

        return $this;
    }

    /**
     * Load options from ajax results.
     *
     * @param string $url
     * @param $idField
     * @param $textField
     *
     * @return $this
     */
    public function ajax($url, $idField = 'id', $textField = 'text')
    {
        $configs = array_merge([
            'allowClear'         => true,
            'placeholder'        => $this->label,
            'minimumInputLength' => 1,
        ], $this->config);

        $configs = json_encode($configs);
        $configs = substr($configs, 1, strlen($configs) - 2);

        $this->script = <<<EOT

$("{$this->getElementClassSelector()}").select2({
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
  $configs,
  escapeMarkup: function (markup) {
      return markup;
  }
});

EOT;

        return $this;
    }

    /**
     * Set config for select2.
     *
     * all configurations see https://select2.org/configuration/options-api
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function config($key, $val)
    {
        $this->config[$key] = $val;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $configs = array_merge([
            'allowClear'  => true,
            'placeholder' => $this->label,
        ], $this->config);

        $configs = json_encode($configs);

        if (empty($this->script)) {
            $this->script = "$(\"{$this->getElementClassSelector()}\").select2($configs);";
        }

        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }

            $this->options(call_user_func($this->options, $this->value));
        }

        $this->options = array_filter($this->options, 'strlen');

        $this->addVariables([
            'options' => $this->options,
            'groups'  => $this->groups,
        ]);

        $this->attribute('data-value', implode(',', (array) $this->value()));

        return parent::render();
    }
}
