<?php

namespace Encore\Admin\Show;

use Encore\Admin\Show;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Field implements Renderable
{
    /**
     * @var string
     */
    protected $view = 'admin::show.field';

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
     * Field value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * @var Collection
     */
    protected $showAs = [];

    /**
     * Parent show instance.
     *
     * @var Show
     */
    protected $parent;

    /**
     * Relation name.
     *
     * @var string
     */
    protected $relation;

    /**
     * Field constructor.
     *
     * @param string $name
     * @param string $label
     */
    public function __construct($name = '', $label = '')
    {
        $this->name = $name;

        $this->label = $this->formatLabel($label);

        $this->showAs = new Collection();
    }

    /**
     * Set parent show instance.
     *
     * @param Show $show
     *
     * @return $this
     */
    public function setParent(Show $show)
    {
        $this->parent = $show;

        return $this;
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
     * Field display callback.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function as(callable $callable)
    {
        $this->showAs->push($callable);

        return $this;
    }

    /**
     * Display field using array value map.
     *
     * @param array $values
     * @param null  $default
     *
     * @return $this
     */
    public function using(array $values, $default = null)
    {
        return $this->as(function ($value) use ($values, $default) {
            if (is_null($value)) {
                return $default;
            }

            return array_get($values, $value, $default);
        });
    }

    /**
     * Show field as a image.
     *
     * @param string $server
     * @param int    $width
     * @param int    $height
     *
     * @return $this
     */
    public function image($server = '', $width = 200, $height = 200)
    {
        return $this->as(function ($path) use ($server, $width, $height) {
            if (url()->isValidUrl($path)) {
                $src = $path;
            } elseif ($server) {
                $src = $server.$path;
            } else {
                $src = Storage::disk(config('admin.upload.disk'))->url($path);
            }

            return "<img src='$src' style='max-width:{$width}px;max-height:{$height}px' class='img' />";
        });
    }

    /**
     * Show field as a link.
     *
     * @param string $href
     * @param string $target
     *
     * @return Field
     */
    public function link($href = '', $target = '_blank')
    {
        return $this->as(function ($link) use ($href, $target) {
            $href = $href ?: $link;

            return "<a href='$href' target='{$target}'>{$link}</a>";
        });
    }

    /**
     * Show field as labels.
     *
     * @param string $style
     *
     * @return Field
     */
    public function label($style = 'success')
    {
        return $this->as(function ($value) use ($style) {
            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            }

            return collect((array) $value)->map(function ($name) use ($style) {
                return "<span class='label label-{$style}'>$name</span>";
            })->implode('&nbsp;');
        });
    }

    /**
     * Show field as badges.
     *
     * @param string $style
     *
     * @return Field
     */
    public function badge($style = 'blue')
    {
        return $this->as(function ($value) use ($style) {
            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            }

            return collect((array) $value)->map(function ($name) use ($style) {
                return "<span class='badge bg-{$style}'>$name</span>";
            })->implode('&nbsp;');
        });
    }

    /**
     * Set value for this field.
     *
     * @param Model $model
     *
     * @return $this
     */
    public function setValue(Model $model)
    {
        if ($this->relation) {
            if (!$model->{$this->relation}) {
                return $this;
            }

            $this->value = $model->{$this->relation}->getAttribute($this->name);
        } else {
            $this->value = $model->getAttribute($this->name);
        }

        return $this;
    }

    /**
     * Set relation name for this field.
     *
     * @param string $relation
     *
     * @return $this
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments = [])
    {
        if ($this->relation) {
            $this->name = $method;
            $this->label = $this->formatLabel(array_get($arguments, 0));
        }

        return $this;
    }

    /**
     * Render this field.
     *
     * @return string
     */
    public function render()
    {
        if ($this->showAs->isNotEmpty()) {
            $this->showAs->each(function ($callable) {
                $this->value = $callable->call(
                    $this->parent->getModel(),
                    $this->value
                );
            });
        }

        $content = $this->value;
        $label = $this->getLabel();

        return view($this->view, compact('content', 'label'));
    }
}
