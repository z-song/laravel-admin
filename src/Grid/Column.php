<?php

namespace Encore\Admin\Grid;

use Closure;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\URL;

class Column
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Name of column.
     *
     * @var string
     */
    protected $name;

    /**
     * Label of column.
     *
     * @var string
     */
    protected $label;

    /**
     * Original value of column.
     *
     * @var mixed
     */
    protected $original;

    /**
     * Is column sortable.
     *
     * @var bool
     */
    protected $sortable = false;

    /**
     * Sort arguments.
     *
     * @var array
     */
    protected $sort;

    /**
     * Attributes of column.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Value wrapper.
     *
     * @var \Closure
     */
    protected $valueWrapper;

    /**
     * Html wrapper.
     *
     * @var array
     */
    protected $htmlWrappers = [];

    /**
     * Relation name.
     *
     * @var bool
     */
    protected $relation = false;

    /**
     * Relation column.
     *
     * @var string
     */
    protected $relationColumn;

    /**
     * @param string $name
     * @param string $label
     */
    public function __construct($name, $label)
    {
        $this->name = $name;

        $this->label = $this->formatLabel($label);
    }

    /**
     * @param Grid $grid
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Get name of this column.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Format label.
     *
     * @param $label
     *
     * @return mixed
     */
    protected function formatLabel($label)
    {
        $label = $label ?: ucfirst($this->name);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Get label of the column.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add a value wrapper.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function value(Closure $callable)
    {
        $this->valueWrapper = $callable;

        return $this;
    }

    /**
     * Alias for value method.
     *
     * @param callable $callable
     * @return $this
     */
    public function display(Closure $callable)
    {
        return $this->value($callable);
    }

    /**
     * If has a value wrapper.
     *
     * @return bool
     */
    protected function hasValueWrapper()
    {
        return (bool) $this->valueWrapper;
    }

    /**
     * Set relation.
     *
     * @param $relation
     *
     * @return $this
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * If this column is relation column.
     *
     * @return bool
     */
    protected function isRelation()
    {
        return (bool) $this->relation;
    }

    /**
     * Map all data to every column.
     *
     * @param $data
     * @return mixed
     */
    public function map($data)
    {
        foreach ($data as &$item) {
            $this->original = $value = array_get($item, $this->name);

            if ($this->hasValueWrapper()) {
                $value = call_user_func($this->valueWrapper, $value);
                array_set($item, $this->name, $value);
            }

            if ($this->hasHtmlWrapper()) {
                $value = $this->htmlWrap($value, $item);
                array_set($item, $this->name, $value);
            }
        }

        return $data;
    }

    /**
     * Mark this column as sortable.
     *
     * @return Column
     */
    public function sortable()
    {
        $this->sortable = true;

        return $this;
    }

    /**
     * Wrap value with badge.
     *
     * @param string $style
     *
     * @return $this
     */
    public function badge($style = 'red')
    {
        $wrapper = "<span class='badge bg-{$style}'>{value}</span>";

        $this->htmlWrapper($wrapper);

        return $this;
    }

    /**
     * Wrap value with label.
     *
     * @param string $style
     *
     * @return $this
     */
    public function label($style = 'success')
    {
        $wrapper = "<span class='label label-{$style}'>{value}</span>";

        $this->htmlWrapper($wrapper);

        return $this;
    }

    /**
     * Wrap value as a link.
     *
     * @param $href
     * @param string $target
     *
     * @return $this
     */
    public function link($href = '', $target = '_blank')
    {
        if (empty($href)) {
            $href = '{$value}';
        }

        $wrapper = "<a href='$href' target='$target'>{value}</a>";

        $this->htmlWrapper($wrapper);

        return $this;
    }

    /**
     * Wrap value as a button.
     *
     * @param string $style
     *
     * @return $this
     */
    public function button($style = 'default')
    {
        if (is_array($style)) {
            $style = array_map(function ($style) {
                return 'btn-'.$style;
            }, $style);

            $style = implode(' ', $style);
        } elseif (is_string($style)) {
            $style = 'btn-'.$style;
        }

        $wrapper = "<span class='btn $style'>{value}</span>";

        $this->htmlWrapper($wrapper);

        return $this;
    }

    /**
     * Wrap value as a progressbar.
     *
     * @param string $style
     * @param string $size
     * @param int    $max
     *
     * @return $this
     */
    public function progressBar($style = 'primary', $size = 'sm', $max = 100)
    {
        if (is_array($style)) {
            $style = array_map(function ($style) {
                return 'progress-bar-'.$style;
            }, $style);

            $style = implode(' ', $style);
        } elseif (is_string($style)) {
            $style = 'progress-bar-'.$style;
        }

        $wrapper = <<<EOT

<div class="progress progress-$size">
    <div class="progress-bar $style" role="progressbar" aria-valuenow="{value}" aria-valuemin="0" aria-valuemax="$max" style="width: {value}%">
      <span class="sr-only">{value}</span>
    </div>
</div>

EOT;

        $this->htmlWrapper($wrapper);

        return $this;
    }

    /**
     * Wrap value as a image.
     *
     * @param string $server
     * @param int    $width
     * @param int    $height
     *
     * @return $this
     */
    public function image($server = '', $width = 200, $height = 200)
    {
        $server = $server ?: config('admin.upload.host');

        $wrapper = "<img src='$server/{\$value}' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";

        $this->htmlWrapper($wrapper);

        return $this;
    }

    /**
     * Make the column editable.
     *
     * @return $this
     */
    public function editable()
    {
        $editable = new Editable($this->name, func_get_args());
        $editable->setResource($this->grid->resource());

        $this->htmlWrapper($editable->html());

        return $this;
    }

    /**
     * Set html wrapper.
     *
     * @param $wrapper
     */
    protected function htmlWrapper($wrapper)
    {
        $this->htmlWrappers[] = $wrapper;
    }

    /**
     * If column has html wrapper.
     *
     * @return bool
     */
    protected function hasHtmlWrapper()
    {
        return !empty($this->htmlWrappers);
    }

    /**
     * Wrap value with wrapper.
     *
     * @param $value
     *
     * @return mixed
     */
    protected function htmlWrap($value, $row = [])
    {
        foreach ($this->htmlWrappers as $wrapper) {
            $value = str_replace('{value}', $value, $wrapper);
        }

        $value = str_replace('{$value}', is_null($this->original) ? 'NULL' : $this->original, $value);
        $value = str_replace('{pk}', array_get($row, $this->grid->getKeyName()), $value);

        return $value;
    }

    /**
     * Create the column sorter.
     *
     * @return string|void
     */
    public function sorter()
    {
        if (!$this->sortable) {
            return;
        }

        $icon = 'fa-sort';
        $type = 'desc';

        if ($this->isSorted()) {
            $type = $this->sort['type'] == 'desc' ? 'asc' : 'desc';
            $icon .= "-amount-{$this->sort['type']}";
        }

        $query = app('request')->all();
        $query = array_merge($query, ['_sort' => ['column' => $this->name, 'type' => $type]]);

        $url = Url::current().'?'.http_build_query($query);

        return "<a class=\"fa fa-fw $icon\" href=\"$url\"></a>";
    }

    /**
     * Determine if this column is currently sorted.
     *
     * @return bool
     */
    protected function isSorted()
    {
        $this->sort = app('request')->get('_sort');

        if (empty($this->sort)) {
            return false;
        }

        return isset($this->sort['column']) && $this->sort['column'] == $this->name;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if ($this->isRelation()) {
            $this->name = "{$this->relation}.$method";
            $this->label = isset($arguments[0]) ? $arguments[0] : ucfirst($method);

            $this->relationColumn = $method;

            return $this;
        }
    }
}
