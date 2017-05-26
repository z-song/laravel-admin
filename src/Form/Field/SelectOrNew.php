<?php

namespace Encore\Admin\Form\Field;


/**
 * Select an existing option or type a new one to create it
 */
class SelectOrNew extends Select
{
    /**
     * @var bool
     */
    protected $optionsSet = false;

    /**
     * @var bool
     */
    protected $allowClear = true;

    /**
     * @var string|null
     */
    protected $dataModel;

    /**
     * @param array $options
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        $this->optionsSet = true;

        return parent::options($options);
    }

    /**
     * Fill in the options by selecting the distinct values for the model's column.
     * Prepends an empty value if $allowClear is true.
     * Only gets called if no other options are set.
     *
     * Does not work in a NestedForm by default. You need to set the dataModel specifically!
     *
     * @return $this
     */
    public function setDefaultOptions()
    {
        $column = $this->column;
        $model = $this->dataModel ?: $this->form->model();
        $options = $model::
        select($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column, $column);

        if ($this->allowClear) {
            $options->prepend('---', '');
        }

        $this->options($options);

        return $this;
    }

    /**
     * Set the datamodel used to get the default data.
     *
     * @param string $className
     */
    public function dataModel(string $className)
    {
        $this->dataModel = $className;
    }

    /**
     * @param bool $allow
     *
     * @return $this
     */
    public function allowClear(bool $allow = true)
    {
        $this->allowClear = $allow;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        if (!$this->optionsSet) {
            $this->setDefaultOptions();
        }
        $allowClear = $this->allowClear ? 'true' : 'false';

        $this->script
            = "$(\"{$this->getElementClassSelector()}\").select2({
            allowClear: {$allowClear},
            tags: true,
            placeholder: \"{$this->label}\",
            tokenSeparators: [',']
        });";

        return parent::render();
    }
}
