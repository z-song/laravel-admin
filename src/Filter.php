<?php

namespace Encore\Admin;

use Encore\Admin\Filter\Is;
use Encore\Admin\Filter\Like;
use Encore\Admin\Grid\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;

class Filter
{
    protected $model;

    protected $fields = [];

    public function __construct(Model $model)
    {
        $this->model = $model->eloquent();

        $this->is($this->model->getKeyName());
    }
    
    public function is($column, $label = '')
    {
        $this->addField(new Is($column, $label));

        return $this;
    }

    public function like($column, $label = '')
    {
        $this->addField(new Like($column, $label));

        return $this;
    }

    public function between($column, $label = '')
    {

    }

    public function gt($column, $label = '')
    {

    }

    public function lt($column, $label = '')
    {

    }

    protected function addField($field)
    {
        $this->fields[] = $field;
    }

    protected function fields()
    {
        return $this->fields;
    }

    /**
     * Get all conditions of the filter fields.
     *
     * @return array
     */
    public function conditions()
    {
        $inputs = array_filter(Input::all());
        $inputs = Arr::dot($inputs);

        $conditions = [];

        foreach($this->fields() as $field) {
            $conditions[] = $field->condition($inputs);
        }

        return array_filter($conditions);
    }

    public function render()
    {
        return view('admin::filter')->with(['fields' => $this->fields()]);
    }

    public function __toString()
    {
        return $this->render();
    }
}