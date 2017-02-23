<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultipleFile extends Field
{
    use UploadField;

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

        $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $attributes[$this->column] = $this->label;

        list($rules, $input) = $this->hydrateFiles(array_get($input, $this->column, []));

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
            $rules[$this->column . $key] = $this->getRules();
            $input[$this->column . $key] = $file;
        }

        return [$rules, $input];
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
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return $this->destroy(request(static::FILE_DELETE_FLAG));
        }

        $targets = array_map([$this, 'prepareForeach'], $files);

        return array_merge($this->original(), $targets);
    }

    /**
     * @return array|mixed
     */
    public function original()
    {
        if (empty($this->original)) {
            return [];
        }

        return $this->original;
    }

    /**
     * Prepare for each file.
     *
     * @param UploadedFile $file
     *
     * @return mixed|string
     */
    protected function prepareForeach(UploadedFile $file = null)
    {
        $this->name = $this->getStoreName($file);

        return $this->upload($file);
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return array
     */
    protected function preview()
    {
        $files = $this->value ?: [];

        return array_map([$this, 'objectUrl'], $files);
    }

    /**
     * Initialize the caption.
     *
     * @param array $caption
     *
     * @return string
     */
    protected function initialCaption($caption)
    {
        if (empty($caption)) {
            return '';
        }

        $caption = array_map('basename', $caption);

        return implode(',', $caption);
    }

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $files = $this->value ?: [];

        $config = [];

        foreach ($files as $index => $file) {
            $config[] = [
                'caption' => basename($file),
                'key'     => $index,
            ];
        }

        return $config;
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->attribute('multiple', true);

        $this->setupDefaultOptions();

        if (!empty($this->value)) {
            $this->setupPreviewOptions();
        }

        $options = json_encode($this->options);

        $this->script = <<<EOT
$("input{$this->getElementClassSelector()}").fileinput({$options});
EOT;

        return parent::render();
    }

    /**
     * Destroy original files.
     *
     * @return string.
     */
    public function destroy($key)
    {
        $files = $this->original ?: [];

        $file = array_get($files, $key);

        if ($this->storage->exists($file)) {
            $this->storage->delete($file);
        }

        unset($files[$key]);

        return array_values($files);
    }
}
