<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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
            if (class_exists($options) && in_array(Model::class, class_parents($options))) {
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

    public function template(array $view)
    {
        $view = array_intersect_key($view, array_flip(['result', 'selection']));
        if ($view) {
            $this->config['escapeMarkup'] = 'function (markup) {return markup;}';
            foreach ($view as $key => $val) {
                $key = ucfirst(strtolower($key));
                $func_key = "template{$key}";
                $func_name = str_replace('.', '', "{$this->getElementClassSelector()}_{$key}");
                $this->config[$func_key] = $func_name;
                $script = implode("\n", [
                    "{$func_name} = function(data) {",
                    "\tif ( !data.id || data.loading) return data.text;",
                    $val,
                    '}',
                ]);
                Admin::script($script);
            }
        }

        return $this;
    }

    private function buildJsJson(array $options, array $functions = [])
    {
        $functions = array_merge([
            'ajax',
            'escapeMarkup',
            'templateResult',
            'templateSelection',
            'initSelection',
            'sorter',
            'tokenizer',
        ], $functions);

        return implode(
            ",\n",
            array_map(function ($u, $v) use ($functions) {
                if (is_string($v)) {
                    return  in_array($u, $functions) ? "{$u}: {$v}" : "{$u}: \"{$v}\"";
                }

                return "{$u}: ".json_encode($v);
            }, array_keys($options), $options)
        );
    }

    private function configs($default = [], $quoted = false)
    {
        $configs = array_merge(
            [
                'allowClear'  => true,
                'language'    => app()->getLocale(),
                'placeholder' => [
                    'id'   => '',
                    'text' => $this->label,
                ],
                'escapeMarkup' => 'function (markup) {return markup;}',
            ],
            $default,
            $this->config
        );
        $configs = $this->buildJsJson($configs);

        return $quoted ? '{'.$configs.'}' : $configs;
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
        config=window._config[".{$class}"];
        config.data=$.map(data, function (d) {
            d.id = d.$idField;
            d.text = d.$textField;
            return d;
        });
        $(target).select2(config).trigger('change');

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

var refreshOptions = function(url, target, name) {
    $.get(url).then(function(data) {
        target.find("option").remove();
        config=window._config[name];
        config.data=$.map(data, function (d) {
            d.id = d.$idField;
            d.text = d.$textField;
            return d;
        });
        $(target).select2(config).trigger('change');

    });
};

$(document).off('change', "{$this->getElementClassSelector()}");
$(document).on('change', "{$this->getElementClassSelector()}", function () {
    var _this = this;
    var promises = [];

    fields.forEach(function(field, index){
        var target = $(_this).closest('.fields-group').find('.' + fields[index]);
        promises.push(refreshOptions(urls[index] + "?q="+ _this.value, target, name));
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
     * @param string $model
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function model($model, $idField = 'id', $textField = 'name')
    {
        if (
            !class_exists($model)
            || !in_array(Model::class, class_parents($model))
        ) {
            throw new \InvalidArgumentException("[$model] must be a valid model class");
        }

        $this->options = function ($value) use ($model, $idField, $textField) {
            if (empty($value)) {
                return [];
            }

            $resources = [];

            if (is_array($value)) {
                if (Arr::isAssoc($value)) {
                    $resources[] = array_get($value, $idField);
                } else {
                    $resources = array_column($value, $idField);
                }
            } else {
                $resources[] = $value;
            }

            return $model::find($resources)->pluck($textField, $idField)->toArray();
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

        $configs = $this->configs([
            'allowClear'         => true,
            'placeholder'        => [
                'id'        => '',
                'text'      => trans('admin.choose'),
            ],
        ]);

        $ajaxOptions = json_encode(array_merge($ajaxOptions, $options));

        $this->script = <<<EOT

$.ajax($ajaxOptions).done(function(data) {

  var select = $("{$this->getElementClassSelector()}");

  select.select2({
    data: data,
    $configs
  });
  
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
        $configs = $this->configs([
            'allowClear'         => true,
            'placeholder'        => $this->label,
            'minimumInputLength' => 1,
        ]);

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
        Admin::js('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/i18n/'.app()->getLocale().'.js');
        $configs = str_replace("\n", '', $this->configs(
            [
                'allowClear'  => true,
                'placeholder' => [
                    'id'   => '',
                    'text' => $this->label,
                ],
            ],
            true
        ));
        Admin::script("if(!window.hasOwnProperty('_config')) window._config=new Object();");
        Admin::script("window._config['{$this->getElementClassSelector()}']=eval('({$configs})');\n");

        if (empty($this->script)) {
            $this->script = "$(\"{$this->getElementClassSelector()}\").select2({$configs});";
        }

        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }

            $this->options(call_user_func($this->options, $this->value, $this));
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
