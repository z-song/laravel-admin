<?php

namespace Encore\Admin\Grid\Column;

use Carbon\Carbon;
use Encore\Admin\Grid\Column;
use Encore\Admin\Grid\Displayers;
use Encore\Admin\Grid\Model;
use Illuminate\Support\Arr;

/**
 * Trait ExtendDisplay
 *
 * @method $this editable()
 * @method $this image($server = '', $width = 200, $height = 200)
 * @method $this label($style = 'success')
 * @method $this button($style = null)
 * @method $this link($href = '', $target = '_blank')
 * @method $this badge($style = 'red')
 * @method $this progress($style = 'primary', $size = 'sm', $max = 100)
 * @method $this orderable($column, $label = '')
 * @method $this table($titles = [])`
 * @method $this expand($callback = null)
 * @method $this modal($title, $callback = null)
 * @method $this carousel(int $width = 300, int $height = 200, $server = '')
 * @method $this downloadable($server = '')
 * @method $this copyable()
 * @method $this qrcode($formatter = null, $width = 150, $height = 150)
 * @method $this prefix($prefix, $delimiter = '&nbsp;')
 * @method $this suffix($suffix, $delimiter = '&nbsp;')
 * @method $this secret($dotCount = 6)
 * @method $this limit($limit = 100, $end = '...')
 */
trait ExtendDisplay
{
    /**
     * Displayers for grid column.
     *
     * @var array
     */
    public static $displayers = [
        'editable'      => Displayers\Editable::class,
        'image'         => Displayers\Image::class,
        'label'         => Displayers\Label::class,
        'button'        => Displayers\Button::class,
        'link'          => Displayers\Link::class,
        'badge'         => Displayers\Badge::class,
        'progressBar'   => Displayers\ProgressBar::class,
        'progress'      => Displayers\ProgressBar::class,
        'orderable'     => Displayers\Orderable::class,
        'table'         => Displayers\Table::class,
        'expand'        => Displayers\Expand::class,
        'modal'         => Displayers\Modal::class,
        'carousel'      => Displayers\Carousel::class,
        'downloadable'  => Displayers\Downloadable::class,
        'copyable'      => Displayers\Copyable::class,
        'qrcode'        => Displayers\QRCode::class,
        'prefix'        => Displayers\Prefix::class,
        'suffix'        => Displayers\Suffix::class,
        'secret'        => Displayers\Secret::class,
        'limit'         => Displayers\Limit::class,
    ];

    /**
     * @var bool
     */
    protected $searchable = false;

    /**
     * Extend column displayer.
     *
     * @param $name
     * @param $displayer
     */
    public static function extend($name, $displayer)
    {
        static::$displayers[$name] = $displayer;
    }

    /**
     * Set column as searchable.
     *
     * @return $this
     */
    public function searchable()
    {
        $this->searchable = true;

        $name = $this->getName();
        $query = request()->query();

        $this->prefix(function ($_, $original) use ($name, $query) {
            Arr::set($query, $name, $original);

            $url = request()->fullUrlWithQuery($query);

            return "<a href=\"{$url}\"><i class=\"fa fa-search\"></i></a>";
        }, '&nbsp;&nbsp;');

        return $this;
    }

    /**
     * Bind search query to grid model.
     *
     * @param Model $model
     */
    public function bindSearchQuery(Model $model)
    {
        if ($this->searchable && ($value = request($this->getName())) != '') {
            $model->where($this->getName(), $value);
        }
    }

    /**
     * Display column using array value map.
     *
     * @param array $values
     * @param null  $default
     *
     * @return $this
     */
    public function using(array $values, $default = null)
    {
        return $this->display(function ($value) use ($values, $default) {
            if (is_null($value)) {
                return $default;
            }

            return Arr::get($values, $value, $default);
        });
    }

    /**
     * Replace output value with giving map.
     *
     * @param array $replacements
     *
     * @return $this
     */
    public function replace(array $replacements)
    {
        return $this->display(function ($value) use ($replacements) {
            if (isset($replacements[$value])) {
                return $replacements[$value];
            }

            return $value;
        });
    }

    /**
     * @param string|Closure $input
     * @param string $seperator
     *
     * @return $this
     */
    public function repeat($input, $seperator = '')
    {
        if (is_string($input)) {
            $input = function () use ($input) {
                return $input;
            };
        }

        if ($input instanceof Closure) {
            return $this->display(function ($value) use ($input, $seperator) {
                return join($seperator, array_fill(0, (int) $value, $input->call($this, [$value])));
            });
        }

        return $this;
    }

    /**
     * Render this column with the given view.
     *
     * @param string $view
     *
     * @return $this
     */
    public function view($view)
    {
        return $this->display(function ($value) use ($view) {
            $model = $this;

            return view($view, compact('model', 'value'))->render();
        });
    }

    /**
     * Convert file size to a human readable format like `100mb`.
     *
     * @return $this
     */
    public function filesize()
    {
        return $this->display(function ($value) {
            return file_size($value);
        });
    }

    /**
     * Display the fields in the email format as gavatar.
     *
     * @param int $size
     *
     * @return $this
     */
    public function gravatar($size = 30)
    {
        return $this->display(function ($value) use ($size) {
            $src = sprintf(
                'https://www.gravatar.com/avatar/%s?s=%d',
                md5(strtolower($value)),
                $size
            );

            return "<img src='$src' class='img img-circle'/>";
        });
    }

    /**
     * Display field as a loading icon.
     *
     * @param array $values
     * @param array $others
     *
     * @return $this
     */
    public function loading($values = [], $others = [])
    {
        return $this->display(function ($value) use ($values, $others) {
            $values = (array) $values;

            if (in_array($value, $values)) {
                return '<i class="fa fa-refresh fa-spin text-primary"></i>';
            }

            return Arr::get($others, $value, $value);
        });
    }

    /**
     * Display column as an font-awesome icon based on it's value.
     *
     * @param array  $setting
     * @param string $default
     *
     * @return $this
     */
    public function icon(array $setting, $default = '')
    {
        return $this->display(function ($value) use ($setting, $default) {
            $fa = '';

            if (isset($setting[$value])) {
                $fa = $setting[$value];
            } elseif ($default) {
                $fa = $default;
            }

            return "<i class=\"fa fa-{$fa}\"></i>";
        });
    }

    /**
     * Return a human readable format time.
     *
     * @param null $locale
     *
     * @return $this
     */
    public function diffForHumans($locale = null)
    {
        if ($locale) {
            Carbon::setLocale($locale);
        }

        return $this->display(function ($value) {
            return Carbon::parse($value)->diffForHumans();
        });
    }

    /**
     * Display column as boolean , `✓` for true, and `✗` for false.
     *
     * @param array $map
     * @param bool  $default
     *
     * @return $this
     */
    public function bool(array $map = [], $default = false)
    {
        return $this->display(function ($value) use ($map, $default) {
            $bool = empty($map) ? boolval($value) : Arr::get($map, $value, $default);

            return $bool ? '<i class="fa fa-check text-green"></i>' : '<i class="fa fa-close text-red"></i>';
        });
    }

    /**
     * Display column as a default value if empty.
     *
     * @param string $default
     * @return $this
     */
    public function default($default = '-')
    {
        return $this->display(function ($value) use ($default) {
            return $value ?: $default;
        });
    }

    /**
     * Add a `dot` before column text.
     *
     * @param array  $options
     * @param string $default
     *
     * @return $this
     */
    public function dot($options = [], $default = '')
    {
        return $this->prefix(function ($_, $original) use ($options, $default) {
            if (is_null($original)) {
                $style = $default;
            } else {
                $style = Arr::get($options, $original, $default);
            }

            return "<span class=\"label-{$style}\" style='width: 8px;height: 8px;padding: 0;border-radius: 50%;display: inline-block;'></span>";
        }, '&nbsp;&nbsp;');
    }
}
