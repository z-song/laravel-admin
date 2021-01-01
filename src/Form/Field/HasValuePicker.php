<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

/**
 * @mixin Field
 */
trait HasValuePicker
{
    /**
     * @var ValuePicker
     */
    protected $picker;

    /**
     * @param string $picker
     * @param string $column
     *
     * @return $this
     */
    public function pick($picker, $column = '')
    {
        $this->picker = new ValuePicker($picker, $column);

        return $this;
    }

    /**
     * @param string $picker
     * @param string $column
     * @param string $separator
     */
    public function pickMany($picker, $column = '', $separator = ';')
    {
        $this->picker = new ValuePicker($picker, $column, true, $separator);

        return $this;
    }

    /**
     * @return $this
     */
    protected function mountPicker()
    {
        $this->picker && $this->picker->mount($this);

        return $this;
    }

    /**
     * @return string
     */
    public function getRules()
    {
        $rules = parent::getRules();

        array_delete($rules, 'image');

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function renderFilePicker()
    {
        $this->mountPicker()
            ->setView('admin::form.filepicker')
            ->attribute('type', 'text')
            ->attribute('id', $this->id)
            ->attribute('name', $this->elementName ?: $this->formatName($this->column))
            ->attribute('value', $this->value())
            ->attribute('class', 'form-control '.$this->getElementClassString())
            ->attribute('placeholder', $this->getPlaceholder())
            ->addVariables([
                'picker'  => $this->picker,
                'preview' => $this->picker->getPreview(get_called_class()),
            ]);

        return Admin::view('admin::form.filepicker', $this->variables());
    }
}
