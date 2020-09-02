<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class ValuePicker
{
    /**
     * @var string
     */
    protected $modal;

    /**
     * @var Text|File
     */
    protected $field;

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $selecteable;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * ValuePicker constructor.
     *
     * @param string $selecteable
     * @param string $column
     * @param bool   $multiple
     * @param string $separator
     */
    public function __construct($selecteable, $column = '', $multiple = false, $separator = ';')
    {
        $this->selecteable = $selecteable;
        $this->column = $column;
        $this->multiple = $multiple;
        $this->separator = $separator;
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $selectable = str_replace('\\', '_', $this->selecteable);

        $args = [$this->multiple, $this->column];

        return route('admin.handle-selectable', compact('selectable', 'args'));
    }

    /**
     * @param Field         $field
     * @param \Closure|null $callback
     */
    public function mount(Field $field, \Closure $callback = null)
    {
        $this->field = $field;
        $this->modal = sprintf('picker-modal-%s', $field->getElementClassString());

        $this->addPickBtn($callback);

        Admin::component('admin::components.filepicker', [
            'url'       => $this->getLoadUrl(),
            'modal'     => $this->modal,
            'selector'  => $this->field->getElementClassSelector(),
            'separator' => $this->separator,
            'multiple'  => $this->multiple,
            'is_file'   => $this->field instanceof File,
            'is_image'  => $this->field instanceof Image,
            'url_tpl'   => $this->field instanceof File ? $this->field->objectUrl('__URL__') : '',
        ]);
    }

    /**
     * @param \Closure|null $callback
     */
    protected function addPickBtn(\Closure $callback = null)
    {
        $text = admin_trans('admin.browse');

        $btn = <<<HTML
<a class="btn btn-primary" data-toggle="modal" data-target="#{$this->modal}">
    <i class="fa fa-folder-open"></i>  {$text}
</a>
HTML;

        if ($callback) {
            $callback($btn);
        } else {
            $this->field->addVariables(compact('btn'));
        }
    }

    /**
     * @param string $field
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function getPreview(string $field)
    {
        if (empty($value = $this->field->value())) {
            return [];
        }

        if ($this->multiple) {
            $value = explode($this->separator, $value);
        }

        return collect(Arr::wrap($value))->map(function ($item) use ($field) {
            return [
                'url'     => $this->field->objectUrl($item),
                'value'   => $item,
                'is_file' => $field == File::class,
            ];
        });
    }
}
