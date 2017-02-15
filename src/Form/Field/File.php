<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
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
        '/packages/admin/bootstrap-fileinput/css/fileinput.min.css?v=4.3.7',
    ];

    /**
     * Js.
     *
     * @var array
     */
    protected static $js = [
        '/packages/admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js?v=4.3.7',
        '/packages/admin/bootstrap-fileinput/js/fileinput.min.js?v=4.3.7',
    ];

    /**
     * If use unique name to store upload file.
     *
     * @var bool
     */
    protected $useUniqueName = false;

    /**
     * Use multiple upload.
     *
     * @var bool
     */
    protected $multiple = false;

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
        $this->disk(config('admin.upload.disk'));
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
     * Set field as mulitple upload.
     *
     * @return $this
     */
    public function multiple()
    {
        $this->attribute('multiple', true);

        $this->multiple = true;

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
     * Set disk for storage.
     *
     * @param string $disk Disks defined in `config/filesystems.php`.
     *
     * @return $this
     */
    public function disk($disk)
    {
        if (!array_key_exists($disk, config('filesystems.disks'))) {
            $error = new MessageBag([
                'title'   => 'Config error.',
                'message' => "Disk [$disk] not configured, please add a disk config in `config/filesystems.php`.",
            ]);

            return session()->flash('error', $error);
        }

        $this->storage = Storage::disk($disk);

        return $this;
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
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $rules[$this->column] = $fieldRules;
        $attributes[$this->column] = $this->label;

        if ($this->multiple) {
            list($rules, $input) = $this->hydrateFiles(array_get($input, $this->column, []));
        }

        return Validator::make($input, $rules, [], $attributes);
    }

    /**
     * Hydrate the files array.
     *
     * @param array $value
     *
     * @return array
     */
    protected function hydrateFiles(array $value)
    {
        $rules = $input = [];

        foreach ($value as $key => $file) {
            $rules[$this->column.$key] = $this->getRules();
            $input[$this->column.$key] = $file;
        }

        return [$rules, $input];
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
     * @param UploadedFile|array $files
     *
     * @return mixed|string
     */
    public function prepare($files)
    {
        $original = (array) json_decode($this->original, true);

        if (!$files instanceof UploadedFile && !is_array($files)) {
            if ($this->handleDeleteRequest()) {
                return '';
            }

            if (is_string($files)) {
                if (($key = array_search($files, $original))) {
                    $this->destroyItem($files);
                    array_splice($original, $key, 1);
                }

                return $this->value = json_encode($original);
            }

            return $this->original;
        }

        if ($this->multiple || is_array($files)) {
            $targets = array_map([$this, 'prepareForSingle'], $files);

            return json_encode(array_merge($targets, $original));

//	        return json_encode($targets);
        }

        return $this->prepareForSingle($files);
    }

    /**
     * Prepare for single file.
     *
     * @param UploadedFile $file
     *
     * @return mixed|string
     */
    protected function prepareForSingle(UploadedFile $file = null)
    {
        $this->directory = $this->directory ?: $this->defaultStorePath();

        $this->name = $this->getStoreName($file);

//	    return $this->uploadAndDeleteOriginal($file);
        return $this->upload($file);
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
     * Get directory for store file.
     *
     * @return mixed|string
     */
    public function getDirectory()
    {
        if ($this->directory instanceof \Closure) {
            return call_user_func($this->directory, $this->form);
        }

        return $this->directory;
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
        $target = $this->upload($file);

        $this->destroy();

        return $target;
    }

    protected function upload(UploadedFile $file)
    {
        $this->renameIfExists($file);

        $target = $this->getDirectory().'/'.$this->name;

        $this->storage->put($target, file_get_contents($file->getRealPath()));

        return $target;
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return array
     */
    protected function preview()
    {
        $files = $this->value;

        if (is_string($this->value)) {
            $files = json_decode($this->value, true);
        }

        if (!is_array($files)) {
            $files = [$this->value];
        }

        $preview = [];

        if (!empty($files)) {
            $preview['initialPreview'] = array_map(function ($file) {
                return  "/upload/$file";
            }, $files);
            $preview['initialPreviewAsData'] = true;
            $preview['initialPreviewConfig'] = $this->initialPreviewConfig($files);
        }

        return $preview;

//        return array_map([$this, 'buildPreviewItem'], $files);
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
     * Initialize the caption.
     *
     * @param string $caption
     *
     * @return string
     */
    protected function initialCaption($caption)
    {
        if (empty($caption)) {
            return '';
        }

        if ($this->multiple) {
            if (is_string($caption)) {
                $caption = json_decode($caption, true);
            }
        }

        if (!is_array($caption)) {
            $caption = [$caption];
        }

//        $caption = array_map('basename', $caption);

//        return implode(',', $caption);
        $count = count($caption);

        return "$count files selected";
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $pk = $this->form->builder()->getPk();
        $this->options['initialCaption'] = $this->initialCaption($this->value);
        $this->options['overwriteInitial'] = false;
        $this->options['deleteUrl'] = $this->form->resource(-2).'/'.$pk;
        $this->options['deleteExtraData'] = ['pk'=> $pk, 'name' => $this->column, '_delfile' => 1, '_token' => csrf_token(), '_method' => 'PUT'];
        $this->options['removeLabel'] = trans('admin::lang.remove');
        $this->options['browseLabel'] = trans('admin::lang.browse');

        if (!empty($this->value)) {
            //            $this->options['initialPreview'] = $this->preview();
            $this->options = array_merge($this->options, $this->preview());
        }

        $options = str_replace('\\/', '/', json_encode($this->options));

        $class = $this->getElementClass();

        $this->script = <<<EOT

$("input.{$class}").fileinput({$options});

$("input.{$class}").on('filecleared', function(event) {
    $(".{$class}_action").val(1);
});

EOT;

        return parent::render()->with(['multiple' => $this->multiple, 'actionName' => $this->getActionName()]);
    }

    /**
     * get the file info from fiels array.
     *
     * @param $files
     *
     * @return array
     */
    private function initialPreviewConfig($files)
    {
        $info = [];
        $uploadPath = public_path('upload').'/';
        foreach ($files as $k => $file) {
            $info[$k]['filename'] = basename($file);
            $info[$k]['key'] = $file;
            if (empty($file) || !file_exists($uploadPath.$file)) {
                continue;
            }
            $info[$k]['filesize'] = filesize($uploadPath.$file);
            $info[$k]['filetype'] = mime_content_type($uploadPath.$file);
        }

        return $info;
    }

    /**
     * Get action element name.
     *
     * @return array|mixed|string
     */
    protected function getActionName()
    {
        return $this->formatName($this->column.'_action');
    }

    /**
     * If is delete request then delete original image.
     *
     * @return bool
     */
    public function handleDeleteRequest()
    {
        $action = Input::get($this->column.'_action');

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
        if ($this->storage->exists("{$this->getDirectory()}/$this->name")) {
            $this->name = $this->generateUniqueName($file);
        }
    }

    /**
     * Destroy original files.
     *
     * @return void.
     */
    public function destroy()
    {
        $files = $this->original;

        if (is_string($this->original)) {
            $files = json_decode($this->original, true);
        }

        if (!is_array($files)) {
            $files = [$this->original];
        }

        array_map([$this, 'destroyItem'], $files);
    }

    /**
     * Destroy single original file.
     *
     * @param $item
     *
     * @return bool
     */
    protected function destroyItem($item)
    {
        if ($this->storage->exists($item)) {
            $this->storage->delete($item);
        }
    }
}
