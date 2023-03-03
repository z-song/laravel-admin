<?php

namespace Encore\Admin\Form\Field;

use Illuminate\Support\Str;

class MorphTo extends BelongsTo
{
    protected $typeColumn;

    protected $morphClass;

    public function __construct($morphName, $arguments = [])
    {
        $this->typeColumn = $morphName.'_type';
        $column = $morphName.'_id';
        parent::__construct($column, $arguments);

        if (empty($arguments[1])) {
            $this->label = Str::title($morphName);
        }

        $this->addVariables([
            'typeColumn' => $this->typeColumn,
            'morphClass' => $this->morphClass(),
        ]);
    }

    /**
     * @return mixed
     */
    public function morphClass()
    {
        if (!$this->morphClass) {
            $modelClass = (new $this->selectable())->model;
            $this->morphClass = (new $modelClass())->getMorphClass();
        }

        return $this->morphClass;
    }

    public function value($value = null)
    {
        if (!is_null($value)) {
            return parent::value($value);
        }

        if ($this->currenMorphClass() == $this->morphClass()) {
            return parent::value();
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function currenMorphClass()
    {
        return $this->form->model()->{$this->typeColumn};
    }
}
