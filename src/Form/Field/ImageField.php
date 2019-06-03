<?php

namespace Encore\Admin\Form\Field;

use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image as InterventionImage;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageField
{
    /**
     * Intervention calls.
     *
     * @var array
     */
    protected $interventionCalls = [];

    /**
     * Thumbnail sizes.
     *
     * @var array
     */
    protected $sizes = [];

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.directory.image');
    }

    /**
     * Execute Intervention calls.
     *
     * @param string $target
     *
     * @return mixed
     */
    public function callInterventionMethods($target)
    {
        if (!empty($this->interventionCalls)) {
            $image = ImageManagerStatic::make($target);

            foreach ($this->interventionCalls as $call) {
                call_user_func_array(
                    [$image, $call['method']],
                    $call['arguments']
                )->save($target);
            }
        }

        return $target;
    }

    /**
     * Call intervention methods.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if (static::hasMacro($method)) {
            return $this;
        }

        if (!class_exists(ImageManagerStatic::class)) {
            throw new \Exception('To use image handling and manipulation, please install [intervention/image] first.');
        }

        $this->interventionCalls[] = [
            'method'    => $method,
            'arguments' => $arguments,
        ];

        return $this;
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
     * @param string $name
     * @param int    $width
     * @param int    $height
     *
     * @return $this
     */
    public function addThumbnailSize(string $name, int $width, int $height)
    {
        $this->sizes[] = compact('name', 'width', 'height');

        return $this;
    }

    /**
     * Destroy original thumbnail files.
     *
     * @return void.
     */
    public function destroyThumbnail()
    {
        foreach ($this->sizes as $size) {
            // We need to get extension type ( .jpeg , .png ...)
            $ext = pathinfo($this->original, PATHINFO_EXTENSION);

            // We remove extension from file name so we can append thumbnail type
            $name = str_replace_last('.'.$ext, '', $this->original);

            // We merge original name + thumbnail name + extension
            $name = $name.'-'.$size['name'].'.'.$ext;

            if ($this->storage->exists($name)) {
                $this->storage->delete($name);
            }
        }
    }

    /**
     * Upload file and delete original thumbnail files.
     *
     * @param UploadedFile $file
     *
     * @return $this
     */
    protected function uploadAndDeleteOriginalThumbnail(UploadedFile $file)
    {
        foreach ($this->sizes as $size) {
            // We need to get extension type ( .jpeg , .png ...)
            $ext = pathinfo($this->name, PATHINFO_EXTENSION);

            // We remove extension from file name so we can append thumbnail type
            $name = str_replace_last('.'.$ext, '', $this->name);

            // We merge original name + thumbnail name + extension
            $name = $name.'-'.$size['name'].'.'.$ext;

            // Resize image with aspect ratio
            $image = InterventionImage::make($file);
            $image->resize($size['width'], $size['height'], function (Constraint $constraint) {
                $constraint->aspectRatio();
            });

            if (!is_null($this->storage_permission)) {
                $this->storage->put("{$this->getDirectory()}/{$name}", $image->encode(), $this->storage_permission);
            } else {
                $this->storage->put("{$this->getDirectory()}/{$name}", $image->encode());
            }
        }

        $this->destroyThumbnail();

        return $this;
    }
}