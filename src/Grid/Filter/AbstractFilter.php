<?php

namespace Encore\Admin\Grid\Filter;

use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Filter\Presenter\ {
    Presenter, Checkbox, DateTime, MultipleSelect, Radio, Select, Text
};

/**
 * Class AbstractFilter
 * @package Encore\Admin\Grid\Filter
 *
 * @method Text url()
 * @method Text email()
 * @method Text integer()
 * @method Text decimal($options = [])
 * @method Text currency($options = [])
 * @method Text percentage($options = [])
 * @method Text ip()
 * @method Text mac()
 * @method Text mobile($mask = '19999999999')
 * @method Text inputmask($options = [], $icon = '')
 * @method Text placeholder($placeholder = '')
 *
 */
abstract class AbstractFilter
{
    /**
     * Element id.
     *
     * @var array|string
     */
    protected $id;

    /**
     * Label of presenter.
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
     * Presenter object.
     *
     * @var Presenter
     */
    protected $presenter;

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

        $this->setupDefaultPresenter();
    }

    /**
     * Setup default presenter.
     *
     * @return void
     */
    protected function setupDefaultPresenter()
    {
        $this->setPresenter(new Text($this->label));
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
    protected function formatId($columns)
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
     * @return Select
     */
    public function select($options = [])
    {
        return $this->setPresenter(new Select($options));
    }

    /**
     * @param array $options
     *
     * @return MultipleSelect
     */
    public function multipleSelect($options = [])
    {
        return $this->setPresenter(new MultipleSelect($options));
    }

    /**
     * @param array $options
     *
     * @return Radio
     */
    public function radio($options = [])
    {
        return $this->setPresenter(new Radio($options));
    }

    /**
     * @param array $options
     *
     * @return Checkbox
     */
    public function checkbox($options = [])
    {
        return $this->setPresenter(new Checkbox($options));
    }

    /**
     * Datetime filter.
     *
     * @param array $options
     *
     * @return DateTime
     */
    public function datetime($options = [])
    {
        return $this->setPresenter(new DateTime($options));
    }

    /**
     * Date filter.
     *
     * @return DateTime
     */
    public function date()
    {
        return $this->datetime(['format' => 'YYYY-MM-DD']);
    }

    /**
     * Time filter.
     *
     * @return DateTime
     */
    public function time()
    {
        return $this->datetime(['format' => 'HH:mm:ss']);
    }

    /**
     * Day filter.
     *
     * @return DateTime
     */
    public function day()
    {
        return $this->datetime(['format' => 'DD']);
    }

    /**
     * Month filter.
     *
     * @return DateTime
     */
    public function month()
    {
        return $this->datetime(['format' => 'MM']);
    }

    /**
     * Year filter.
     *
     * @return DateTime
     */
    public function year()
    {
        return $this->datetime(['format' => 'YYYY']);
    }

    /**
     * Set presenter object of filter.
     *
     * @param Presenter $presenter
     *
     * @return mixed
     */
    protected function setPresenter(Presenter $presenter)
    {
        $presenter->setParent($this);

        return $this->presenter = $presenter;
    }

    /**
     * Get presenter object of filter.
     *
     * @return Presenter
     */
    protected function presenter()
    {
        return $this->presenter;
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
    protected function presenterVars()
    {
        if (method_exists($this->presenter(), 'variables')) {
            return $this->presenter()->variables();
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
            'id'        => $this->id,
            'name'      => $this->formatName($this->column),
            'label'     => $this->label,
            'value'     => $this->value,
            'presenter' => $this->presenter(),
        ];

        return array_merge($variables, $this->presenterVars());
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
        if (method_exists($this->presenter, $method)) {
            return call_user_func_array([$this->presenter, $method], $params);
        }

        throw new \Exception('Method "'.$method.'" not exists.');
    }
}
