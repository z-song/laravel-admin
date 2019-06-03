<?php

namespace Encore\Admin\Grid\Concerns;

use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Tools\QuickSearch;
use Illuminate\Support\Str;

trait HasQuickSearch
{
    /**
     * @var string
     */
    public static $searchKey = '__search__';

    /**
     * @var array|string|\Closure
     */
    protected $search;

    /**
     * @param array|string|\Closure
     * @return $this
     */
    public function quickSearch($search = null)
    {
        if (func_num_args() > 1) {
            $this->search = func_get_args();
        } else {
            $this->search = $search;
        }

        $this->tools->append(new QuickSearch());

        return $this;
    }

    /**
     * Apply the search query to the query.
     *
     * @return mixed|void
     */
    protected function applyQuickSearch()
    {
        if (!$query = request()->get(static::$searchKey)) {
            return;
        }

        if ($this->search instanceof \Closure) {
            return call_user_func($this->search, $this->model(), $query);
        }

        if (is_string($this->search)) {
            $this->search = [$this->search];
        }

        if (is_array($this->search)) {
            foreach ($this->search as $column) {
                $this->applyWhereLikeQuery($column, true, '%'.$query.'%');
            }
        } elseif (is_null($this->search)) {
            $this->dispatchSearchQuery($query);
        }
    }

    protected function mappingColumnAndConditions($queries)
    {
        $columnMap = $this->columns->mapWithKeys(function (Column $column) {

            $label = $column->getLabel();
            $name  = $column->getName();

            return [$label => $name, $name => $name];
        });

        return collect($queries)->map(function ($query) use ($columnMap) {
            $segments = explode(':', $query, 2);

            if (count($segments) != 2) {
                return;
            }

            $or = false;

            list($column, $condition) = $segments;

            if (Str::startsWith($column, '|')) {
                $or = true;
                $column = substr($column, 1);
            }

            $column = $columnMap[$column];

            return [$column, $condition, $or];
        })->filter()->toArray();
    }

    protected function dispatchSearchQuery($query)
    {
        $queries = preg_split('/\s(?=([^"]*"[^"]*")*[^"]*$)/', trim($query));

        $mapping = $this->mappingColumnAndConditions($queries);

        foreach ($mapping as list($column, $condition, $or)) {

            if (preg_match('/(?<not>!?)\((?<values>.+)\)/', $condition, $match) !== 0) {
                $this->applyWhereInQuery($column, $or, $match['not'], $match['values']);
                continue;
            }

            if (preg_match('/\[(?<start>.*?),(?<end>.*?)]/', $condition, $match) !== 0) {
                $this->applyWhereBetweenQuery($column, $or, $match['start'], $match['end']);
                continue;
            }

            if (preg_match('/(?<function>date|time|day|month|year),(?<value>.*)/', $condition, $match) !== 0) {
                $this->applyWhereDatetimeQuery($column, $or, $match['function'], $match['value']);
                continue;
            }

            if (preg_match('/(?<pattern>%[^%]+%)/', $condition, $match) !== 0) {
                $this->applyWhereLikeQuery($column, $or, $match['pattern']);
                continue;
            }

            if (preg_match('/\/(?<value>.*)\//', $condition, $match) !== 0) {
                $this->applyWhereQuery($column, $or, 'REGEXP', $match['value']);
                continue;
            }

            if (preg_match('/(?<operator>>=?|<=?|!=|%){0,1}(?<value>.*)/', $condition, $match) !== 0) {
                $this->applyWhereQuery($column, $or, $match['operator'], $match['value']);
                continue;
            }
        }
    }

    protected function applyWhereLikeQuery($column, $or, $pattern)
    {
        $connectionType = $this->model()->eloquent()->getConnection()->getDriverName();
        $likeOperator   = $connectionType == 'pgsql' ? 'ilike' : 'like';

        $method = $or ? 'orWhere' : 'where';

        $this->model()->{$method}($column, $likeOperator, $pattern);
    }

    protected function applyWhereDatetimeQuery($column, $or, $function, $value)
    {
        $method = ($or ? 'orWhere' : 'where') . ucfirst($function);

        $this->model()->$method($column, $value);
    }

    protected function applyWhereInQuery($column, $or, $not, $values)
    {
        $values = explode(',', $values);

        foreach ($values as $key => $value) {
            if ($value === 'NULL') {
                $values[$key] = null;
            }
        }

        $method = $or ? 'orWhereIn' : 'whereIn';

        $this->model()->$method($column, $values);
    }

    protected function applyWhereBetweenQuery($column, $or, $start, $end)
    {
        $method = $or ? 'orWhereBetween' : 'whereBetween';

        $this->model()->$method($column, [$start, $end]);
    }

    protected function applyWhereQuery($column, $or, $operator, $value)
    {
        $method = $or ? 'orWhere' : 'where';

        $operator = $operator ?: '=';

        if ($operator == '%') {
            $operator = 'like';
            $value = "%{$value}%";
        }

        if ($value === 'NULL') {
            $value = null;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            $value = substr($value, 1, -1);
        }

        $this->model()->{$method}($column, $operator, $value);
    }
}