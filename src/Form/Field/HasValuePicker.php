<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Admin;
use Illuminate\Support\Arr;

trait HasValuePicker
{
    /**
     * @var ValuePicker
     */
    protected $picker;

    /**
     * @var string
     */
    protected $btn = '';

    /**
     * @param string $picker
     * @param string $column
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
     * @param string $delimiter
     */
    public function pickMultiple($picker, $column = '', $delimiter = ';')
    {
        $this->picker = new ValuePicker($picker, $column, true, $delimiter);

        return $this;
    }

    /**
     * @return void
     */
    protected function mountPicker()
    {
        if ($this->picker) {
            $this->picker->mount($this);
            $this->addVariables(['btn' => $this->btn]);
        }
    }

    /**
     * @return string
     */
    protected function getRules()
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
        $this->view = 'admin::form.filepicker';

        $this->picker->mount($this);

        $this->attribute('type', 'text')
            ->attribute('id', $this->id)
            ->attribute('name', $this->elementName ?: $this->formatName($this->column))
            ->attribute('value', old($this->elementName ?: $this->column, $this->value()))
            ->attribute('class', 'form-control '.$this->getElementClassString())
            ->attribute('placeholder', $this->getPlaceholder());

        $this->addVariables([
            'preview' => $this->picker->preview(get_called_class()),
            'btn'     => $this->btn
        ]);

        return parent::render();
    }

    /**
     * @param string $wrap
     */
    public function addPickBtn($btn)
    {
        $this->btn = $btn;
    }
}
