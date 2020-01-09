<?php

namespace Encore\Admin\Form\Field;

use Illuminate\Support\Str;
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
     * Thumbnail settings.
     *
     * @var array
     */
    protected $thumbnails = [];

    /**
     * @var array
     * quality: the webp image's quality, 0 means no webp will generate
     * thumb: whether thumbnail webp will be generated or not
     */
    protected $webp = [
        'quality' => 0,
        'thumb' => true,
    ];

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
        $this->options(['allowedFileTypes' => ['image'], 'msgPlaceholder' => trans('admin.choose_image')]);

        return parent::render();
    }

    /**
     * @param string|array $name
     * @param int          $width
     * @param int          $height
     *
     * @return $this
     */
    public function thumbnail($name, int $width = null, int $height = null)
    {
        if (func_num_args() == 1 && is_array($name)) {
            foreach ($name as $key => $size) {
                if (count($size) >= 2) {
                    $this->thumbnails[$key] = $size;
                }
            }
        } elseif (func_num_args() == 3) {
            $this->thumbnails[$name] = [$width, $height];
        }

        return $this;
    }

    /**
     * Destroy original thumbnail files.
     *
     * @return void.
     */
    public function destroyThumbnail()
    {
        if ($this->retainable) {
            return;
        }

        $webp = $this->webp;
        foreach ($this->thumbnails as $name => $_) {
            // We need to get extension type ( .jpeg , .png ...)
            $ext = pathinfo($this->original, PATHINFO_EXTENSION);

            // We remove extension from file name so we can append thumbnail type
            $fileName = Str::replaceLast('.'.$ext, '', $this->original);

            // We merge original name + thumbnail name + extension
            $path = $fileName.'-'.$name.'.'.$ext;

            if ($this->storage->exists($path)) {
                $this->storage->delete($path);
            }

            if ($webp['quality'] && $webp['thumb']) {
                $webpThumbPath = $fileName.'-'.$name.'.webp';
                if ($this->storage->exists($webpThumbPath)) {
                    $this->storage->delete($webpThumbPath);
                }
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
        $webp = $this->webp;
        foreach ($this->thumbnails as $name => $size) {
            // We need to get extension type ( .jpeg , .png ...)
            $ext = pathinfo($this->name, PATHINFO_EXTENSION);

            // We remove extension from file name so we can append thumbnail type
            $fileName = Str::replaceLast('.'.$ext, '', $this->name);

            // We merge original name + thumbnail name + extension
            $path = $fileName.'-'.$name.'.'.$ext;

            /** @var \Intervention\Image\Image $image */
            $image = InterventionImage::make($file);

            $action = $size[2] ?? 'resize';
            // Resize image with aspect ratio
            $image->$action($size[0], $size[1], function (Constraint $constraint) {
                $constraint->aspectRatio();
            })->resizeCanvas($size[0], $size[1], 'center', false, '#ffffff');

            $this->storage->put("{$this->getDirectory()}/{$path}", $image->encode(), $this->storagePermission ?? null);

            if ($webp['quality'] && $webp['thumb']) {
                // generate webp via thumbnail image
                $webpThumbPath = $fileName.'-'.$name.'.webp';
                $this->storage->put("{$this->getDirectory()}/{$webpThumbPath}", $image->encode('webp', $webp['quality']), $this->storagePermission ?? null);
            }
        }

        $this->destroyThumbnail();

        return $this;
    }

    /**
     * generate webp and delete original webp
     * @param UploadedFile $file
     * @return $this
     */
    protected function generateWebpAndDeleteOriginal(UploadedFile $file)
    {
        $webp = $this->webp;

        if ($webp['quality']) {
            $ext = pathinfo($this->name, PATHINFO_EXTENSION);
            $path = Str::replaceLast('.'.$ext, '', $this->name).'.webp';
            $image = InterventionImage::make($file);

            $this->storage->put("{$this->getDirectory()}/{$path}", $image->encode(), $this->storagePermission ?? null);
            $this->destoryWebp();
        }

        return $this;
    }

    protected function destoryWebp()
    {
        if ($this->retainable) {
            return;
        }

        $ext = pathinfo($this->original, PATHINFO_EXTENSION);

        // We remove extension from file name so we can append thumbnail type
        $path = Str::replaceLast('.'.$ext, '', $this->original).'.webp';

        if ($this->storage->exists($path)) {
            $this->storage->delete($path);
        }
    }

    /**
     * @param int $quality
     * @param bool $thumb
     * @return $this
     */
    public function webp(int $quality=70, bool $thumb=true)
    {
        $this->webp = [
            'quality' => $quality,
            'thumb' => $thumb
        ];
        return $this;
    }
}