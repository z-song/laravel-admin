<?php

namespace Encore\Admin\Form\Field;

class MorphTo extends BelongsTo
{
    protected $typeColumn;

    protected $morphClass;

    protected $view = 'admin::form.belongsTo';

    public function __construct($column, $arguments = [])
    {
        $this->typeColumn = $column . '_type';
        parent::__construct($column . '_id', $arguments);
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

    /**
     * @return mixed
     */
    public function morphClass()
    {
        if (!$this->morphClass) {
            $modelClass = (new $this->selectable)->model;
            $this->morphClass = (new $modelClass)->getMorphClass();
        }
        return $this->morphClass;
    }
}
