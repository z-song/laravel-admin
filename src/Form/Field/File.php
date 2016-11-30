<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Field
{
    const ACTION_KEEP = 0;
    const ACTION_REMOVE = 1;

    /**
     * Upload directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * File name.
     *
     * @var null
     */
    protected $name = null;

    /**
     * Options for file-upload plugin.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Storage instance.
     *
     * @var string
     */
    protected $storage = '';

    /**
     * Css.
     *
     * @var array
     */
    protected static $css = [
        '/packages/admin/bootstrap-fileinput/css/fileinput.min.css',
    ];

    /**
     * Js.
     *
     * @var array
     */
    protected static $js = [
        '/packages/admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js',
        '/packages/admin/bootstrap-fileinput/js/fileinput.min.js',
    ];

    /**
     * If use unique name to store upload file.
     *
     * @var bool
     */
    protected $useUniqueName = false;

    /**
     * Create a new File instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->initOptions();
        $this->initStorage();

        parent::__construct($column, $arguments);
    }

    /**
     * Initialize the storage instance.
     *
     * @return void.
     */
    protected function initStorage()
    {
        $this->storage = Storage::disk(config('admin.upload.disk'));
    }

    /**
     * Initialize file-upload plugin.
     *
     * @return void.
     */
    protected function initOptions()
    {
        $this->options = [
            'overwriteInitial'  => true,
            'showUpload'        => false,
            'language'          => config('app.locale'),
        ];
    }

    /**
     * Set options for file-upload plugin.
     *
     * @param array $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Default store path for file upload.
     *
     * @return mixed
     */
    public function defaultStorePath()
    {
        return config('admin.upload.directory.file');
    }

    /**
     * Specify the directory and name for uplaod file.
     *
     * @param string      $directory
     * @param null|string $name
     *
     * @return $this
     */
    public function move($directory, $name = null)
    {
        $this->directory = $directory;

        $this->name = $name;

        return $this;
    }

    /**
     * Set name of store name.
     *
     * @param string|callable $name
     *
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Use unique name for store upload file.
     *
     * @return $this
     */
    public function uniqueName()
    {
        $this->useUniqueName = true;

        return $this;
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile $file
     *
     * @return mixed|string
     */
    public function prepare(UploadedFile $file = null)
    {
        if (is_null($file)) {
            if ($this->isDeleteRequest()) {
                return '';
            }

            return $this->original;
        }

        $this->directory = $this->directory ?: $this->defaultStorePath();

        $this->name = $this->getStoreName($file);

        return $this->uploadAndDeleteOriginal($file);
    }

    /**
     * Get store name of upload file.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function getStoreName(UploadedFile $file)
    {
        if ($this->useUniqueName) {
            return $this->generateUniqueName($file);
        }

        if (is_callable($this->name)) {
            $callback = $this->name->bindTo($this);

            return call_user_func($callback, $file);
        }

        if (is_string($this->name)) {
            return $this->name;
        }

        return $file->getClientOriginalName();
    }

    /**
     * Upload file and delete original file.
     *
     * @param UploadedFile $file
     *
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file)
    {
        $this->renameIfExists($file);

        $target = $this->directory.'/'.$this->name;

        $this->storage->put($target, file_get_contents($file->getRealPath()));

        $this->destroy();

        return $target;
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return string
     */
    protected function preview()
    {
        $fileName = basename($this->value);

        return <<<EOT
<div class="file-preview-other-frame">
   <div class="file-preview-other">
   <span class="file-icon-4x"><i class="fa fa-file"></i></span>
</div>
   </div>
   <div class="file-preview-other-footer"><div class="file-thumbnail-footer">
    <div class="file-footer-caption">{$fileName}</div>
</div></div>
EOT;
    }

    /**
     * Get file visit url.
     *
     * @param $path
     *
     * @return string
     */
    public function objectUrl($path)
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return rtrim(config('admin.upload.host'), '/').'/'.trim($path, '/');
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->options['initialCaption'] = basename($this->value);

        if (!empty($this->value)) {
            $this->options['initialPreview'] = $this->preview();
        }

        $options = json_encode($this->options);

        $this->script = <<<EOT

$("#{$this->id}").fileinput({$options});

$("#{$this->id}").on('filecleared', function(event) {
    $("#{$this->id}_action").val(1);
});

EOT;

        return parent::render();
    }

    /**
     * If is delete request then delete original image.
     *
     * @return bool
     */
    public function isDeleteRequest()
    {
        $action = Input::get($this->id.'_action');

        if ($action == static::ACTION_REMOVE) {
            $this->destroy();

            return true;
        }

        return false;
    }

    /**
     * Generate a unique name for uploaded file.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function generateUniqueName(UploadedFile $file)
    {
        return md5(uniqid()).'.'.$file->guessExtension();
    }

    /**
     * If name already exists, rename it.
     *
     * @param $file
     *
     * @return void
     */
    public function renameIfExists(UploadedFile $file)
    {
        if ($this->storage->exists("$this->directory/$this->name")) {
            $this->name = $this->generateUniqueName($file);
        }
    }

    /**
     * Destroy original file.
     *
     * @return void.
     */
    public function destroy()
    {
        if ($this->storage->exists($this->original)) {
            $this->storage->delete($this->original);
        }
    }
}
