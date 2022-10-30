<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePond extends Field
{
    use UploadField {
        UploadField::setupDefaultOptions as uploadFieldSetupDefaultOptions;
    }
    use HasValuePicker;

    /**
     * Css.
     *
     * @var array
     */
    protected static $css = [
        '/vendor/laravel-admin/filepond/filepond.min.css',
        '/vendor/laravel-admin/filepond/filepond-plugin-image-preview.min.css',
    ];

    /**
     * Js.
     *
     * @var array
     */
    protected static $js = [
        '/vendor/laravel-admin/filepond/filepond.min.js',
        '/vendor/laravel-admin/filepond/filepond-plugin-file-validate-type.min.js',
        '/vendor/laravel-admin/filepond/filepond-plugin-image-resize.min.js',
        '/vendor/laravel-admin/filepond/filepond-plugin-image-preview.min.js',
        '/vendor/laravel-admin/filepond/filepond-plugin-image-transform.min.js',
    ];

    /**
     * Filepond use separate server to upload, fetch, etc.
     *
     * @var bool
     */
    protected $useSeparateServer = false;

    /**
     * Is file publicly available on Filepond separate server
     *
     * @var string
     */
    protected bool $isPublic = false;

    /**
     * Filepond separate server URL
     *
     * @var string
     */
    protected $serverUrl;

    /**
     * Separate server verified upload ack URL
     *
     * @var string
     */
    protected $ackUrl;

    /**
     * Image resizing target width
     *
     * @var int
     */
    protected int $targetWidth = 512;

    /**
     * Image resizing target height
     *
     * @var int
     */
    protected int $targetHeight = 512;

    /**
     * Max files
     *
     * @var int
     */
    protected int $maxFiles = 1;

    /**
     * Create a new File instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->initStorage();

        parent::__construct($column, $arguments);
    }

    /**
     * Set image resizing dimensions
     *
     * @param integer $width
     * @param integer $height
     * @return FilePond
     */
    public function setResizeDimensions(int $width = 512, int $height = 512)
    {
        $this->targetWidth = $width;
        $this->targetHeight = $height;

        return $this;
    }

    /**
     * Set max-files
     *
     * @param integer $maxFiles
     * @return FilePond
     */
    public function setMaxFiles(int $maxFiles)
    {
        $this->maxFiles = $maxFiles;

        if ($this->maxFiles > 0) {
            $this->attribute('multiple');
        }

        return $this;
    }

    /**
     * Do you want to use separate server to upload file?
     * If you want call this method
     *
     * @param bool $public Is file publicly available
     * @param string $dir Save file to this directory
     * @param string|null $serverUrl The URL of filepond server
     * @param string|null $ackUrl The URL to call to send acknowledge and verify uploaded file
     * @param string|null $loadUrl The base URL to load file from after upload (not including dir)
     * @return FilePond
     */
    public function useSeparateServer(bool $public = false, string $dir = 'others', string $serverUrl = null, string $ackUrl = null): FilePond
    {
        $this->useSeparateServer = true;

        // Set upload-URL
        $this->serverUrl = $serverUrl;
        if (empty($this->serverUrl)) {
            $this->serverUrl = config('admin.upload.filepond.server');
            if (empty($this->serverUrl)) {
                throw new Exception('Invalid filepond server URL. At least set default server URL in admin config file.');
            }
        }

        // Set ack-URL
        $this->ackUrl = $ackUrl;
        if (empty($this->ackUrl)) {
            $this->ackUrl = config('admin.upload.filepond.api');
            if (empty($this->ackUrl)) {
                throw new Exception('Invalid filepond ack URL. At least set default api URL in admin config file.');
            }
            $this->ackUrl = rtrim($this->ackUrl, '/') . '/ack';
        }

        $this->dir($dir);

        $this->isPublic = $public;

        return $this;
    }

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.directory.file');
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return false;
        }

        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        /*
         * If has original value, means the form is in edit mode,
         * then remove required rule from rules.
         */
        if ($this->original()) {
            $this->removeRule('required');
        }

        /*
         * Make input data validatable if the column data is `null`.
         */
        if (Arr::has($input, $this->column) && is_null(Arr::get($input, $this->column))) {
            $input[$this->column] = '';
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $rules[$this->column] = $fieldRules;
        $attributes[$this->column] = $this->label;

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile|array|string $file
     *
     * @return mixed|string
     */
    public function prepare($file)
    {
        // Check is using filepond media-server
        if ($this->useSeparateServer) {

            // Handle is required
            if (is_null($file) && $this->attributes['required']) {
                return $this->original;
            }

            // Check file has changed?
            if ($this->original === $file) {
                return $file;
            }

            $response = Http::post($this->ackUrl, ['file' => $file, 'dir' => $this->directory, 'public' => $this->isPublic]);
            if ($response->failed()) {
                throw $response->toException();
            }

            $this->value = $file;

            return $this->value;
        } else {
            if (!($file instanceof UploadedFile || is_array($file) || is_string($file))) {
                abort(500, 'Invalid input file on FilePond!');
            }

            if ($this->picker || is_string($file)) {
                return parent::prepare($file);
            }

            if (request()->has(static::FILE_DELETE_FLAG)) {
                return $this->destroy();
            }

            $this->name = $this->getStoreName($file);

            return $this->uploadAndDeleteOriginal($file);
        }
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

        $path = null;

        if (!is_null($this->storagePermission)) {
            $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name, $this->storagePermission);
        } else {
            $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name);
        }

        $this->destroy();

        return $path;
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return string
     */
    protected function preview()
    {
        return $this->objectUrl($this->value);
    }

    /**
     * Hides the file preview.
     *
     * @return $this
     */
    public function hidePreview()
    {
        return $this->options([
            'showPreview' => false,
        ]);
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
        return basename($caption);
    }

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $config = ['caption' => basename($this->value), 'key' => 0];

        $config = array_merge($config, $this->guessPreviewType($this->value));

        return [$config];
    }

    /**
     * Set default options form image field.
     *
     * @return void
     */
    protected function setupDefaultOptions()
    {
        // Call parent
        $this->uploadFieldSetupDefaultOptions();

        // Override it
        $this->options['msgPlaceholder'] = 'جهت درج فایل <span class="filepond--label-action">اینجا</span> کلیک کنید';
    }

    /**
     * @param string $options
     */
    protected function setupScripts()
    {
        $options = [
            'acceptedFileTypes' => ['image/jpeg'],
            'imageResizeTargetHeight' => $this->targetHeight,
            'imageResizeTargetWidth' => $this->targetWidth,
            'imageResizeMode' => 'contain',
            'maxFiles' => $this->maxFiles,
            'labelIdle' => $this->options["msgPlaceholder"],
            'required' => $this->attributes['required'] ?? false,
        ];

        // Set filepond server-url
        if ($this->useSeparateServer) {
            $options['server'] = $this->serverUrl;
            $options['storeAsFile'] = false;
        } else {
            $options['server'] = [
                'load' => $this->storage->url('/'),
            ];
            $options['storeAsFile'] = true;
        }

        if ($this->attributes['data-initial-preview'] ?? false) {
            $options['files'] = [[
                'source' => $this->value,
                'options' => [
                    'type' => 'local',
                ],
            ]];
        }

        $options = json_encode($options, JSON_UNESCAPED_SLASHES);

        // Start script with block-scope
        $this->script = '{';

        $this->script .= <<<EOT
        // FilePond form submit button loading handler
        if (typeof countOfFilePondLoadingPending === 'undefined') {
            var countOfFilePondLoadingPending = 0;
        }

        // Create a FilePond instance
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginImageResize);
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginImageTransform);

        const pond = FilePond.create($("input{$this->getElementClassSelector()}").get(0), $options);

        $(".filepond--root{$this->getElementClassSelector()}").get(-1).addEventListener('FilePond:addfilestart', (e) => {
            countOfFilePondLoadingPending++;
            $(e.target.closest('form')).find('button[type=submit]').prop('disabled', true);
        });

        $(".filepond--root{$this->getElementClassSelector()}").get(-1).addEventListener('FilePond:addfile', (e) => {
            countOfFilePondLoadingPending--;
            if (countOfFilePondLoadingPending === 0) {
                $(e.target.closest('form')).find('button[type=submit]').prop('disabled', false);
            }
        });

        $(".filepond--root{$this->getElementClassSelector()}").get(-1).addEventListener('FilePond:processfilestart', (e) => {
            countOfFilePondLoadingPending++;
            $(e.target.closest('form')).find('button[type=submit]').prop('disabled', true);
        });

        $(".filepond--root{$this->getElementClassSelector()}").get(-1).addEventListener('FilePond:processfile', (e) => {
            countOfFilePondLoadingPending--;
            if (countOfFilePondLoadingPending === 0) {
                $(e.target.closest('form')).find('button[type=submit]').prop('disabled', false);
            }
        });
EOT;

        // Close script block-scope
        $this->script .= '}';
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->setupDefaultOptions();

        if (!empty($this->value)) {
            $this->attribute('data-initial-preview', $this->preview());
            $this->attribute('data-initial-caption', $this->initialCaption($this->value));

            $this->setupPreviewOptions();
        }

        $this->setupScripts();

        return parent::render();
    }
}
