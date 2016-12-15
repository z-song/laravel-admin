<?php

namespace Encore\Admin\Form\Field;

use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    /**
     *  Validation rules.
     *
     * @var string
     */
    protected $rules = 'image';

    /**
     * Intervention calls.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Get default storage path.
     *
     * @return mixed
     */
    public function defaultStorePath()
    {
        return config('admin.upload.directory.image');
    }

    /**
     * Prepare for single upload file.
     *
     * @param UploadedFile|null $image
     *
     * @return string
     */
    protected function prepareForSingle(UploadedFile $image = null)
    {
        $this->directory = $this->directory ?: $this->defaultStorePath();

        $this->name = $this->getStoreName($image);

        $this->executeCalls($image->getRealPath());

        $target = $this->uploadAndDeleteOriginal($image);

        return $target;
    }

    /**
     * Execute Intervention calls.
     *
     * @param string $target
     *
     * @return mixed
     */
    public function executeCalls($target)
    {
        if (!empty($this->calls)) {
            $image = ImageManagerStatic::make($target);

            foreach ($this->calls as $call) {
                call_user_func_array([$image, $call['method']], $call['arguments'])->save($target);
            }
        }

        return $target;
    }

    /**
     * Build a preview item.
     *
     * @param string $image
     *
     * @return string
     */
    protected function buildPreviewItem($image)
    {
        return '<img src="'.$this->objectUrl($image).'" class="file-preview-image">';
    }

    /**
     * Render a image form field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->options(['allowedFileTypes' => ['image']]);

        return parent::render();
    }

    /**
     * Call intervention methods.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (!class_exists(ImageManagerStatic::class)) {
            throw new \Exception('To use image handling and manipulation, please install [intervention/image] first.');
        }

        $this->calls[] = [
            'method'    => $method,
            'arguments' => $arguments,
        ];

        return $this;
    }
}
