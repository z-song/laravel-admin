<?php

namespace Encore\Admin\Filter;

use Illuminate\Support\Arr;
use Encore\Admin\Filter\Field\Text;
use Encore\Admin\Filter\Field\Select;
use Encore\Admin\Filter\Field\DateTime;

abstract class AbstractFilter
{
    protected $id;

    protected $label;

    protected $value;

    protected $column;

    protected $field;

    protected $query = 'where';

    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label  = $this->formatLabel($label);
        $this->id     = $this->formatId($column);

        $this->setupField();
    }

    /**
     * Setup field
     *
     * @return void
     */
    public function setupField()
    {
        $this->field = new Text();
    }

    /**
     * Format label.
     *
     * @param string $label
     * @return string
     */
    protected function formatLabel($label)
    {
        $label = $label ?: ucfirst($this->column);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Format name.
     *
     * @param string $column
     * @return string
     */
    protected function formatName($column)
    {
        $columns = explode('.', $column);

        if (count($columns) == 1) {
            return $columns[0];
        }

        $name = array_shift($columns);
        foreach ($columns as $column) {
            $name .= "[$column]";
        }

        return $name;
    }

    /**
     * Format id.
     *
     * @param $columns
     * @return array|string
     */
    public function formatId($columns)
    {
        return str_replace('.', '_', $columns);
    }

    /**
     * Get query condition from filter.
     *
     * @param array $inputs
     * @return array|mixed|null
     */
    public function condition($inputs)
    {
        $value = Arr::get($inputs, $this->column);

        if (! isset($value)) {
            return null;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, $this->value);
    }

    /**
     * Select filter.
     *
     * @param array $options
     */
    public function select($options = [])
    {
        $this->setField(new Select($options));
    }

    /**
     * Datetime filter.
     */
    public function datetime()
    {
        $this->setField(new DateTime($this));
    }

    protected function setField($field)
    {
        $this->field = $field;
    }

    public function field()
    {
        return $this->field;
    }

    public function getId()
    {
        return $this->id;
    }

    protected function buildCondition()
    {
        $column = explode('.', $this->column);

        if (count($column) == 1) {

            return [$this->query => func_get_args()];
        }

        return call_user_func_array([$this, 'buildRelationCondition'], func_get_args());
    }

    protected function buildRelationCondition()
    {
        $args = func_get_args();

        list($relation, $args[0]) = explode('.', $this->column);

        return ['whereHas' => [$relation, function ($relation) use ($args) {
            call_user_func_array([$relation, $this->query], $args);
        }]];
    }

    protected function fieldVars()
    {
        if (method_exists($this->field(), 'variables')) {
            return $this->field()->variables();
        }

        return [];
    }

    protected function variables()
    {
        $variables = [
            'id'    => $this->id,
            'name'  => $this->formatName($this->column),
            'label' => $this->label,
            'value' => $this->value,
            'field' => $this->field(),
        ];

        return array_merge($variables, $this->fieldVars());
    }

    public function render()
    {
        $class = explode('\\', get_called_class());
        $view = 'admin::filter.' . strtolower(end($class));

        return view($view, $this->variables());
    }

    public function __toString()
    {
        return $this->render();
    }
}
