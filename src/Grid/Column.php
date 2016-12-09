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
     * Html callback.
     *
     * @var array
     */
    protected $htmlCallbacks = [];

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
     * Original grid data.
     *
     * @var array
     */
    protected static $originalGridData = [];

    /**
     * @var []Closure
     */
    protected $displayCallbacks = [];

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
     * Set original data for column.
     *
     * @param array $input
     */
    public static function setOriginalGridData(array $input)
    {
        static::$originalGridData = $input;
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
        return $this->display(function ($value) use ($style) {
            return "<span class='badge bg-{$style}'>$value</span>";
        });
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
        return $this->display(function ($value) use ($style) {
            return "<span class='label label-{$style}'>$value</span>";
        });
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
        return $this->display(function ($value) use ($href, $target) {
            $href = $href ?: $value;

            return "<a href='$href' target='$target'>$value</a>";
        });
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
        return $this->display(function ($value) use ($style) {
            $style = collect((array) $style)->map(function ($style) {
                return 'btn-'.$style;
            })->implode(' ');

            return "<span class='btn $style'>$value</span>";
        });
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
        return $this->display(function ($value) use ($style, $size, $max) {
            $style = collect((array) $style)->map(function ($style) {
                return 'progress-bar-'.$style;
            })->implode(' ');

            return <<<EOT

<div class="progress progress-$size">
    <div class="progress-bar $style" role="progressbar" aria-valuenow="$value" aria-valuemin="0" aria-valuemax="$max" style="width: $value%">
      <span class="sr-only">$value</span>
    </div>
</div>

EOT;
        });
    }

    /**
     * Wrap value with image tag.
     *
     * @param string $server
     * @param int    $width
     * @param int    $height
     *
     * @return $this
     */
    public function image($server = '', $width = 200, $height = 200)
    {
        return $this->display(function ($path) use ($server, $width, $height) {
            if (!$path) {
                return '';
            }

            if (url()->isValidUrl($path)) {
                $src = $path;
            } else {
                $server = $server ?: config('admin.upload.host');
                $src = trim($server, '/').'/'.trim($path, '/');
            }

            return "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img img-thumbnail' />";
        });
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

        $this->htmlCallback($editable->html());

        return $this;
    }

    /**
     * Alias for `display()` method.
     *
     * @param callable $callable
     *
     * @deprecated please use `display()` method instead.
     *
     * @return $this
     */
    public function value(Closure $callable)
    {
        return $this->display($callable);
    }

    /**
     * Add a display callback.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function display(Closure $callback)
    {
        $this->displayCallbacks[] = $callback;

        return $this;
    }

    /**
     * If has display callbacks.
     *
     * @return bool
     */
    protected function hasDisplayCallbacks()
    {
        return !empty($this->displayCallbacks);
    }

    /**
     * Set html callback.
     *
     * @param $callback
     */
    protected function htmlCallback($callback)
    {
        $this->htmlCallbacks[] = $callback;
    }

    /**
     * If column has html callbacks.
     *
     * @return bool
     */
    protected function hasHtmlCallbacks()
    {
        return !empty($this->htmlCallbacks);
    }

    /**
     * Wrap value with callback.
     *
     * @param $value
     *
     * @return mixed
     */
    protected function callHtmlCallbacks($value, $row = [])
    {
        foreach ($this->htmlCallbacks as $callback) {
            $value = str_replace('{value}', $value, $callback);
        }

        $value = str_replace(
            '{$value}',
            is_null($this->original) ? 'NULL' : $this->htmlEntityEncode($this->original),
            $value
        );
        $value = str_replace('{pk}', array_get($row, $this->grid->getKeyName()), $value);

        return $value;
    }

    /**
     * Call all of the "display" callbacks column.
     *
     * @param mixed $value
     * @param int   $key
     *
     * @return mixed
     */
    protected function callDisplayCallbacks($value, $key)
    {
        foreach ($this->displayCallbacks as $callback) {
            $callback = $this->bindOriginalRow($callback, $key);
            $value = call_user_func($callback, $value);
        }

        return $value;
    }

    /**
     * Set original grid data to column.
     *
     * @param Closure $callback
     * @param int     $key
     *
     * @return Closure
     */
    protected function bindOriginalRow(Closure $callback, $key)
    {
        $originalRow = static::$originalGridData[$key];

        return $callback->bindTo((object) $originalRow);
    }

    /**
     * Fill all data to every column.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function fill(array $data)
    {
        foreach ($data as $key => &$row) {
            $this->original = $value = array_get($row, $this->name);

            $value = $this->htmlEntityEncode($value);

            array_set($row, $this->name, $value);

            if ($this->hasDisplayCallbacks()) {
                $value = $this->callDisplayCallbacks($this->original, $key);
                array_set($row, $this->name, $value);
            }

            if ($this->hasHtmlCallbacks()) {
                $value = $this->callHtmlCallbacks($value, $row);
                array_set($row, $this->name, $value);
            }
        }

        return $data;
    }

    /**
     * Convert characters to HTML entities recursively.
     *
     * @param array|string $item
     *
     * @return mixed
     */
    protected function htmlEntityEncode($item)
    {
        if (is_array($item)) {
            array_walk_recursive($item, function (&$value) {
                $value = htmlentities($value);
            });
        } else {
            $item = htmlentities($item);
        }

        return $item;
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
        $query = array_merge($query, [$this->grid->model()->getSortName() => ['column' => $this->name, 'type' => $type]]);

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
        $this->sort = app('request')->get($this->grid->model()->getSortName());

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
