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
     * @var Text
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
     * @param string $selecteable
     * @param string $column
     * @param bool $multiple
     * @param string $separator
     */
    public function __construct($selecteable, $column = '', $multiple = false, $separator = ',')
    {
        $this->selecteable = $selecteable;
        $this->column      = $column;
        $this->multiple    = $multiple;
        $this->separator   = $separator;
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
     * @return $this
     */
    protected function addPickBtn()
    {
        $text = admin_trans('admin.browse');

        $this->field->addPickBtn(<<<HTML
<a class="btn btn-primary" data-toggle="modal" data-target="#{$this->modal}">
    <i class="fa fa-folder-open"></i>  {$text}
</a>
HTML);
    }

    public function mount(Field $field)
    {
        $this->field = $field;
        $this->modal = sprintf('picker-modal-%s', $field->getElementClassString());

        $this->addPickBtn();

        Admin::component('admin::components.filepicker', [
            'url'       => $this->getLoadUrl(),
            'modal'     => $this->modal,
            'selector'  => $this->field->getElementClassSelector(),
            'separator' => $this->separator,
            'multiple'  => $this->multiple,
            'is_file'   => $this->field instanceof File,
        ]);
    }

    public function preview($field)
    {
        $value = $this->field->value();

        if (empty($value)) {
            return [];
        }

        if ($this->multiple) {
            $value = explode($this->separator, $value);
        }

        $previews = [];

        foreach (Arr::wrap($value) as $item) {

            $content = $field == File::class ? '<i class="glyphicon glyphicon-file"></i>' : "<img src=\"{$item}\"/>";

            $previews[] = [
                'value'   => $item,
                'content' => $content,
            ];
        }

        return $previews;
    }
}
