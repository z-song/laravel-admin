<?php

namespace Encore\Admin\Filter;

use Illuminate\Support\Arr;

class Between extends AbstractFilter
{
    protected function formatName($column)
    {
        $columns = explode('.', $column);

        if(count($columns) == 1) {
            return [
                'start' => $columns[0]['start'],
                'end' => $columns[0]['end']
            ];
        }

        $name = array_shift($columns);

        foreach($columns as $column) {
            $name .= "[$column]";
        }

        return ['start' => $name.'[start]', 'end' => $name.'[end]'];
    }

    public function condition($inputs)
    {
        if(! Arr::has($inputs, $this->column)) {
            return null;
        }

        $this->value = Arr::get($inputs, $this->column);

        $value = array_filter($this->value, function($val) {
            return $val !== '';
        });

        if(empty($value)) return null;

        if(! isset($value['start'])) {
            return $this->buildCondition($this->column, '<=', $value['end']);
        }

        if(! isset($value['end'])) {
            return $this->buildCondition($this->column, '>=', $value['start']);
        }

        $this->query = 'whereBetween';

        return $this->buildCondition($this->column, $this->value);
    }
}