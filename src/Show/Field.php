<?php

namespace Encore\Admin\Show;

use Encore\Admin\Show;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Traits\Macroable;

class Field implements Renderable
{
    use Macroable {
        __call as macroCall;
    }

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
     * If show contents in box.
     *
     * @var bool
     */
    public $wrapped = true;

    /**
     * @var array
     */
    protected $fileTypes = [
        'image'      => 'png|jpg|jpeg|tmp|gif',
        'word'       => 'doc|docx',
        'excel'      => 'xls|xlsx|csv',
        'powerpoint' => 'ppt|pptx',
        'pdf'        => 'pdf',
        'code'       => 'php|js|java|python|ruby|go|c|cpp|sql|m|h|json|html|aspx',
        'archive'    => 'zip|tar\.gz|rar|rpm',
        'txt'        => 'txt|pac|log|md',
        'audio'      => 'mp3|wav|flac|3pg|aa|aac|ape|au|m4a|mpc|ogg',
        'video'      => 'mkv|rmvb|flv|mp4|avi|wmv|rm|asf|mpeg'
    ];

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
     * Show field as a file.
     *
     * @param string $server
     * @param bool $download
     * @return Field
     */
    public function file($server = '', $download = true)
    {
        $field = $this;

        return $this->as(function ($path) use ($server, $download, $field) {

            $name = basename($path);

            $field->wrapped = false;

            $size = $url = '';

            if (url()->isValidUrl($path)) {
                $url = $path;
            } elseif ($server) {
                $url = $server.$path;
            } else {
                $storage = Storage::disk(config('admin.upload.disk'));
                if ($storage->exists($path)) {
                    $url = $storage->url($path);
                    $size = ($storage->size($path)/1000) . 'KB';
                }
            }

            return <<<HTML
<ul class="mailbox-attachments clearfix">
    <li style="margin-bottom: 0;">
      <span class="mailbox-attachment-icon"><i class="fa {$field->getFileIcon($name)}"></i></span>
      <div class="mailbox-attachment-info">
        <div class="mailbox-attachment-name">
            <i class="fa fa-paperclip"></i> {$name}
            </div>
            <span class="mailbox-attachment-size">
              {$size}&nbsp;
              <a href="{$url}" class="btn btn-default btn-xs pull-right" target="_blank"><i class="fa fa-cloud-download"></i></a>
            </span>
      </div>
    </li>
  </ul>
HTML;

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
     * Show field as json code.
     *
     * @return Field
     */
    public function json()
    {
        $field = $this;

        return $this->as(function ($value) use ($field) {

            $content = json_decode($value, true);

            if (json_last_error() == 0) {

                $field->wrapped = false;

                return '<pre><code>'.json_encode($content, JSON_PRETTY_PRINT).'</code></pre>';
            }

            return $value;
        });
    }

    /**
     * Get file icon
     *
     * @param string $file
     * @return string
     */
    public function getFileIcon($file = '')
    {
        $extension = File::extension($file);

        foreach ($this->fileTypes as $type => $regex) {
            if (preg_match("/^($regex)$/i", $extension) !== 0) {
                return "fa-file-{$type}-o";
            }
        }

        return 'fa-file-o';
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
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        if ($this->relation) {
            $this->name = $method;
            $this->label = $this->formatLabel(array_get($arguments, 0));
        }

        return $this;
    }

    /**
     * Get all variables passed to field view.
     *
     * @return array
     */
    protected function variables()
    {
        return [
            'content'   => $this->value,
            'label'     => $this->getLabel(),
            'wrapped'   => $this->wrapped,
        ];
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

        return view($this->view, $this->variables());
    }
}
