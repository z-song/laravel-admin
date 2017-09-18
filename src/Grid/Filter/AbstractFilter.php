<?php

namespace Encore\Admin\Grid\Filter;

use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Filter\Field\DateTime;
use Encore\Admin\Grid\Filter\Field\MultipleSelect;
use Encore\Admin\Grid\Filter\Field\Select;
use Encore\Admin\Grid\Filter\Field\Text;

abstract class AbstractFilter
{
    /**
     * Element id.
     *
     * @var array|string
     */
    protected $id;

    /**
     * Label of field.
     *
     * @var string
     */
    protected $label;

    /**
     * @var array|string
     */
    protected $value;

    /**
     * @var string
     */
    protected $column;

    /**
     * Field object.
     *
     * @var
     */
    protected $field;

    /**
     * Query for filter.
     *
     * @var string
     */
    protected $query = 'where';

    /**
     * @var Filter
     */
    protected $parent;

    /**
     * @var string
     */
    protected $view = 'admin::filter.where';

    /**
     * AbstractFilter constructor.
     *
     * @param $column
     * @param string $label
     */
    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label = $this->formatLabel($label);
        $this->id = $this->formatId($column);

        $this->setupField();
    }

    /**
     * Setup field.
     *
     * @return void
     */
    public function setupField()
    {
        $this->field = new Text();
        $this->field->setPlaceholder($this->label);
    }

    /**
     * Format label.
     *
     * @param string $label
     *
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
     *
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
     *
     * @return array|string
     */
    public function formatId($columns)
    {
        return str_replace('.', '_', $columns);
    }

    /**
     * @param Filter $filter
     */
    public function setParent(Filter $filter)
    {
        $this->parent = $filter;
    }

    /**
     * Get siblings of current filter.
     *
     * @param null $index
     *
     * @return AbstractFilter[]|mixed
     */
    public function siblings($index = null)
    {
        if (!is_null($index)) {
            return array_get($this->parent->filters(), $index);
        }

        return $this->parent->filters();
    }

    /**
     * Get previous filter.
     *
     * @param int $step
     *
     * @return AbstractFilter[]|mixed
     */
    public function previous($step = 1)
    {
        return $this->siblings(
            array_search($this, $this->parent->filters()) - $step
        );
    }

    /**
     * Get next filter.
     *
     * @param int $step
     *
     * @return AbstractFilter[]|mixed
     */
    public function next($step = 1)
    {
        return $this->siblings(
            array_search($this, $this->parent->filters()) + $step
        );
    }

    /**
     * Get query condition from filter.
     *
     * @param array $inputs
     *
     * @return array|mixed|null
     */
    public function condition($inputs)
    {
        $value = array_get($inputs, $this->column);

        if (!isset($value)) {
            return;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, $this->value);
    }

    /**
     * Select filter.
     *
     * @param array $options
     *
     * @return $this
     */
    public function select($options = [])
    {
        $select = new Select($options);

        $select->setParent($this);

        return $this->setField($select);
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function multipleSelect($options = [])
    {
        $select = new MultipleSelect($options);

        $select->setParent($this);

        return $this->setField($select);
    }

    /**
     * Datetime filter.
     *
     * @param array $options
     *
     * @return mixed
     */
    public function datetime($options = [])
    {
        return $this->setField(new DateTime($this, $options));
    }

    /**
     * Date filter.
     *
     * @return mixed
     */
    public function date()
    {
        return $this->datetime(['format' => 'YYYY-MM-DD']);
    }

    /**
     * Time filter.
     *
     * @return mixed
     */
    public function time()
    {
        return $this->datetime(['format' => 'HH:mm:ss']);
    }

    /**
     * Day filter.
     *
     * @return mixed
     */
    public function day()
    {
        return $this->datetime(['format' => 'DD']);
    }

    /**
     * Month filter.
     *
     * @return mixed
     */
    public function month()
    {
        return $this->datetime(['format' => 'MM']);
    }

    /**
     * Year filter.
     *
     * @return mixed
     */
    public function year()
    {
        return $this->datetime(['format' => 'YYYY']);
    }

    /**
     * Set field object of filter.
     *
     * @param $field
     *
     * @return mixed
     */
    protected function setField($field)
    {
        return $this->field = $field;
    }

    /**
     * Get field object of filter.
     *
     * @return mixed
     */
    public function field()
    {
        return $this->field;
    }

    /**
     * Get element id.
     *
     * @return array|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get column name of current filter.
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Get value of current filter.
     *
     * @return array|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Build conditions of filter.
     *
     * @return mixed
     */
    protected function buildCondition()
    {
        $column = explode('.', $this->column);

        if (count($column) == 1) {
            return [$this->query => func_get_args()];
        }

        return call_user_func_array([$this, 'buildRelationCondition'], func_get_args());
    }

    /**
     * Build query condition of model relation.
     *
     * @return array
     */
    protected function buildRelationCondition()
    {
        $args = func_get_args();

        list($relation, $args[0]) = explode('.', $this->column);

        return ['whereHas' => [$relation, function ($relation) use ($args) {
            call_user_func_array([$relation, $this->query], $args);
        }]];
    }

    /**
     * @return array
     */
    protected function fieldVars()
    {
        if (method_exists($this->field(), 'variables')) {
            return $this->field()->variables();
        }

        return [];
    }

    /**
     * Variables for filter view.
     *
     * @return array
     */
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

    /**
     * Render this filter.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view($this->view, $this->variables());
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param $method
     * @param $params
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (method_exists($this->field, $method)) {
            return call_user_func_array([$this->field, $method], $params);
        }

        throw new \Exception('Method "'.$method.'" not exists.');
    }
}
