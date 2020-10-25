<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Select extends Field
{
    use CanCascadeFields;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     *  Data attribute for Option.
     *
     * @var array
     */
    protected $optionDataAttributes = [];

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
     * Set option data attributes.
     *
     * @param string $dataKey
     * @param array|callable $attributes
     *
     * @return $this|mixed
     */
    public function optionDataAttributes($dataKey, $attributes)
    {
        if ($attributes instanceof Arrayable) {
            $attributes = $attributes->toArray();
        }

        $this->optionDataAttributes[$dataKey] = (array) $attributes;

        return $this;
    }

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
    public function load($field, $sourceUrl, $idField = 'id', $textField = 'text', bool $allowClear = true)
    {
        if (Str::contains($field, '.')) {
            $field = $this->formatName($field);
            $class = str_replace(['[', ']'], '_', $field);
        } else {
            $class = $field;
        }

        $strAllowClear = var_export($allowClear, true);

        return $this->addVariables(['load' => compact('class', 'sourceUrl', 'strAllowClear', 'idField', 'textField')]);
    }

    /**
     * Load options for other selects on change.
     *
     * @param array  $fields
     * @param array  $sourceUrls
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function loads($fields = [], $sourceUrls = [], $idField = 'id', $textField = 'text', bool $allowClear = true)
    {
        $fieldsStr = implode('.', $fields);
        $urlsStr = implode('^', $sourceUrls);
        $strAllowClear = var_export($allowClear, true);

        return $this->addVariables(['loads' => compact('fieldsStr', 'urlsStr', 'strAllowClear', 'idField', 'textField')]);
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
        if (!class_exists($model)
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
                    $resources[] = Arr::get($value, $idField);
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

        $configs = array_merge([
            'allowClear'         => true,
            'placeholder'        => [
                'id'        => '',
                'text'      => trans('admin.choose'),
            ],
        ], $this->config);

        $configs = json_encode($configs);
        $configs = substr($configs, 1, strlen($configs) - 2);

        $options = array_merge($ajaxOptions, $options);

        return $this->addVariables(['remote' => compact('options', 'configs')]);
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

        return $this->addVariables(['ajax' => compact('url', 'idField', 'textField', 'configs')]);
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
    public function readonly()
    {
        $this->addVariables(['readonly' => true]);

        return parent::readonly();
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }

            $this->options(call_user_func($this->options, $this->value, $this));
        }

        $this->options = array_filter($this->options, 'strlen');

        return $this->options;
    }

    /**
     * @return array
     */
    protected function getOptionDataAttributes()
    {
        $arrayOptionAttributes = [];
        foreach ($this->optionDataAttributes as $dataKey => $attributes) {
            foreach ($attributes as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value, true);
                }
                $arrayOptionAttributes[$key][] = "data-" . $dataKey . "='" . $value . "'";
            }
        }

        $stringOptionAttributes = [];
        foreach ($arrayOptionAttributes as $attributeKey => $arrayOptionAttribute) {
            $stringOptionAttributes[$attributeKey] = implode(' ', $arrayOptionAttribute);
        }

        return $stringOptionAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $configs = array_merge([
            'allowClear'  => true,
            'placeholder' => [
                'id'   => '',
                'text' => $this->label,
            ],
        ], $this->config);

        $this->addVariables([
            'options' => $this->getOptions(),
            'optionDataAttributes' => $this->getOptionDataAttributes(),
            'groups'  => $this->groups,
            'configs' => $configs,
        ])->attribute('data-value', implode(',', (array) $this->value()));

        $this->addCascadeScript();

        return parent::render();
    }
}
