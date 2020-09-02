<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Field
{
    use UploadField;
    use HasValuePicker;

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
     * @param UploadedFile|array $file
     *
     * @return mixed|string
     */
    public function prepare($file)
    {
        if ($this->picker) {
            return parent::prepare($file);
        }

        if (request()->has(static::FILE_DELETE_FLAG)) {
            return $this->destroy();
        }

        $this->name = $this->getStoreName($file);

        return $this->uploadAndDeleteOriginal($file);
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
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        if ($this->picker) {
            return $this->renderFilePicker();
        }

        $this->options([
            'overwriteInitial' => true,
            'msgPlaceholder'   => trans('admin.choose_file'),
        ]);

        $this->setupDefaultOptions();

        if (!empty($this->value)) {
            $this->attribute('data-initial-preview', $this->preview());
            $this->attribute('data-initial-caption', $this->initialCaption($this->value));

            $this->setupPreviewOptions();
            /*
             * If has original value, means the form is in edit mode,
             * then remove required rule from rules.
             */
            unset($this->attributes['required']);
        }

        $this->addVariables([
            'options'  => json_encode_options($this->options),
            'settings' => $this->fileActionSettings,
        ]);

        return parent::render();
    }
}
