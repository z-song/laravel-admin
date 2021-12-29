<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Tags extends Field
{
    /**
     * @var array
     */
    protected $value = [];

    /**
     * @var bool
     */
    protected $keyAsValue = false;

    /**
     * @var string
     */
    protected $visibleColumn = null;

    /**
     * @var string
     */
    protected $key = null;

    /**
     * @var \Closure
     */
    protected $saveAction = null;

    /**
     * @var array
     */
    protected $separators = [',', ';', '，', '；', ' '];

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
     * {@inheritdoc}
     */
    public function fill($data)
    {
        $this->value = Arr::get($data, $this->column);

        if (is_array($this->value) && $this->keyAsValue) {
            $this->value = array_column($this->value, $this->visibleColumn, $this->key);
        }

        if (is_string($this->value)) {
            $this->value = explode(',', $this->value);
        }

        $this->value = array_filter((array) $this->value, 'strlen');
    }

    /**
     * Set visible column and key of data.
     *
     * @param $visibleColumn
     * @param $key
     *
     * @return $this
     */
    public function pluck($visibleColumn, $key)
    {
        if (!empty($visibleColumn) && !empty($key)) {
            $this->keyAsValue = true;
        }

        $this->visibleColumn = $visibleColumn;
        $this->key = $key;

        return $this;
    }

    /**
     * Set the field options.
     *
     * @param array|Collection|Arrayable $options
     *
     * @return $this|Field
     */
    public function options($options = [])
    {
        if (!$this->keyAsValue) {
            return parent::options($options);
        }

        if ($options instanceof Collection) {
            $options = $options->pluck($this->visibleColumn, $this->key)->toArray();
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = $options + $this->options;

        return $this;
    }

    /**
     * Set Tag Separators.
     *
     * @param array $separators
     *
     * @return $this
     */
    public function separators($separators = [])
    {
        if ($separators instanceof Collection or $separators instanceof Arrayable) {
            $separators = $separators->toArray();
        }
        if (!empty($separators)) {
            $this->separators = $separators;
        }

        return $this;
    }

    /**
     * Set save Action.
     *
     * @param \Closure $saveAction
     *
     * @return $this
     */
    public function saving(\Closure $saveAction)
    {
        $this->saveAction = $saveAction;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        $value = array_filter($value, 'strlen');

        if ($this->keyAsValue) {
            return is_null($this->saveAction) ? $value : ($this->saveAction)($value);
        }

        if (is_array($value) && !Arr::isAssoc($value)) {
            $value = implode(',', $value);
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

        $this->value = (array) $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        $this->setupScript();

        if ($this->keyAsValue) {
            $options = $this->value + $this->options;
        } else {
            $options = array_unique(array_merge($this->value, $this->options));
        }

        return parent::fieldRender([
            'options'    => $options,
            'keyAsValue' => $this->keyAsValue,
        ]);
    }

    protected function setupScript()
    {
        $separators = json_encode($this->separators);
        $separatorsStr = implode('', $this->separators);
        $this->script = <<<JS
$("{$this->getElementClassSelector()}").select2({
    tags: true,
    tokenSeparators: $separators,
    createTag: function(params) {
        if (/[$separatorsStr]/.test(params.term)) {
            var str = params.term.trim().replace(/[$separatorsStr]*$/, '');
            return { id: str, text: str }
        } else {
            return null;
        }
    }
});
JS;

        Admin::script(
            <<<'JS'
$(document).off('keyup', '.select2-selection--multiple .select2-search__field').on('keyup', '.select2-selection--multiple .select2-search__field', function (event) {
    try {
        if (event.keyCode == 13) {
            var $this = $(this), optionText = $this.val();
            if (optionText != "" && $this.find("option[value='" + optionText + "']").length === 0) {
                var $select = $this.parents('.select2-container').prev("select");
                var newOption = new Option(optionText, optionText, true, true);
                $select.append(newOption).trigger('change');
                $this.val('');
                $select.select2('close');
            }
        }
    } catch (e) {
        console.error(e);
    }
});
JS
        );
    }
}
