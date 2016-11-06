<?php

namespace Encore\Admin\Grid\Filter;

class Where extends AbstractFilter
{
    /**
     * Query closure.
     *
     * @var \Closure
     */
    protected $where;

    /**
     * Input value from field.
     *
     * @var
     */
    public $input;

    /**
     * Where constructor.
     *
     * @param \Closure $query
     * @param string   $label
     */
    public function __construct(\Closure $query, $label)
    {
        $this->where = $query;

        $this->column = static::getQueryHash($query);
        $this->label = $this->formatLabel($label);
        $this->id = $this->formatId($this->column);

        $this->setupField();
    }

    /**
     * Get the hash string of query closure.
     *
     * @param \Closure $closure
     *
     * @return string
     */
    public static function getQueryHash(\Closure $closure)
    {
        $reflection = new \ReflectionFunction($closure);

        return md5($reflection->getFileName().$reflection->getStartLine().$reflection->getEndLine());
    }

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return array|mixed|void
     */
    public function condition($inputs)
    {
        $value = array_get($inputs, static::getQueryHash($this->where));

        if (is_array($value)) {
            $value = array_filter($value);
        }

        if (is_null($value) || empty($value)) {
            return;
        }

        $this->input = $this->value = $value;

        return $this->buildCondition($this->where->bindTo($this));
    }
}
