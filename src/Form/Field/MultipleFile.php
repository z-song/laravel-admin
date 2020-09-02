<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultipleFile extends Field
{
    use UploadField;

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
        if (!request()->hasFile($this->column)) {
            return false;
        }

        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $attributes[$this->column] = $this->label;

        list($rules, $input) = $this->hydrateFiles(Arr::get($input, $this->column, []));

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
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
        if (empty($value)) {
            return [[$this->column => $this->getRules()], []];
        }

        $rules = $input = [];

        foreach ($value as $key => $file) {
            $rules[$this->column.'@'.$key] = $this->getRules();
            $input[$this->column.'@'.$key] = $file;
        }

        return [$rules, $input];
    }

    /**
     * Sort files.
     *
     * @param string $order
     *
     * @return array
     */
    protected function sortFiles($order)
    {
        $order = explode(',', $order);

        $new = [];
        $original = $this->original();

        foreach ($order as $item) {
            $new[] = Arr::get($original, $item);
        }

        return $new;
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
            if ($this->pathColumn) {
                return $this->destroyFromHasMany(request(static::FILE_DELETE_FLAG));
            }

            return $this->destroy(request(static::FILE_DELETE_FLAG));
        }

        if (is_string($files) && request()->has(static::FILE_SORT_FLAG)) {
            return $this->sortFiles($files);
        }

        $targets = array_map([$this, 'prepareForeach'], $files);

        // for create or update
        if ($this->pathColumn) {
            $targets = array_map(function ($target) {
                return [$this->pathColumn => $target];
            }, $targets);
        }

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

        return tap($this->upload($file), function () {
            $this->name = null;
        });
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return array
     */
    protected function preview()
    {
        $files = $this->value ?: [];

        return array_values(array_map([$this, 'objectUrl'], $files));
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
            if (is_array($file) && $this->pathColumn) {
                $index = Arr::get($file, $this->getRelatedKeyName(), $index);
                $file = Arr::get($file, $this->pathColumn);
            }

            $preview = array_merge([
                'caption' => basename($file),
                'key'     => $index,
            ], $this->guessPreviewType($file));

            $config[] = $preview;
        }

        return $config;
    }

    /**
     * Get related model key name.
     *
     * @return string
     */
    protected function getRelatedKeyName()
    {
        if (is_null($this->form)) {
            return;
        }

        return $this->form->model()->{$this->column}()->getRelated()->getKeyName();
    }

    /**
     * Allow to sort files.
     *
     * @return $this
     */
    public function sortable()
    {
        $this->fileActionSettings['showDrag'] = true;

        return $this;
    }

    /**
     * Fort validation error message.
     *
     * @return void
     */
    protected function formatValidationMessage()
    {
        if (!($errors = session()->get('errors')) || !($errors instanceof ViewErrorBag)) {
            return;
        }

        $messages = [];

        foreach ($errors->keys() as $key) {
            if (Str::startsWith($key, $this->column.'@')) {
                array_push($messages, ...$errors->get($key));
            }
        }

        if (!empty($messages)) {
            $errors->getBag('default')->merge([$this->column => $messages]);
        }
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
            $this->options(['initialPreview' => $this->preview()]);
            $this->setupPreviewOptions();
        }

        $this->addVariables([
            'options'   => $this->options,
            'settings'  => $this->fileActionSettings,
        ]);

        if ($this->fileActionSettings['showDrag']) {
            $this->addVariables([
                'sortable'  => true,
                'sort_flag' => static::FILE_SORT_FLAG,
            ]);
        }

        $this->formatValidationMessage();

        return parent::render();
    }

    /**
     * Destroy original files.
     *
     * @param string $key
     *
     * @return array
     */
    public function destroy($key)
    {
        $files = $this->original ?: [];

        $path = Arr::get($files, $key);

        if (!$this->retainable && $this->storage->exists($path)) {
            $this->storage->delete($path);
        }

        unset($files[$key]);

        return $files;
    }

    /**
     * Destroy original files from hasmany related model.
     *
     * @param int $key
     *
     * @return array
     */
    public function destroyFromHasMany($key)
    {
        $files = collect($this->original ?: [])->keyBy($this->getRelatedKeyName())->toArray();

        $path = Arr::get($files, "{$key}.{$this->pathColumn}");

        if (!$this->retainable && $this->storage->exists($path)) {
            $this->storage->delete($path);
        }

        $files[$key][Form::REMOVE_FLAG_NAME] = 1;

        return $files;
    }
}
