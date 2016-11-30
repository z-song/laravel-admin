<?php

namespace Encore\Admin\Form\Field;

use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    protected $rules = 'image';

    protected $calls = [];

    public function defaultStorePath()
    {
        return config('admin.upload.directory.image');
    }

    public function prepare(UploadedFile $image = null)
    {
        if (is_null($image)) {
            if ($this->isDeleteRequest()) {
                return '';
            }

            return $this->original;
        }

        $this->directory = $this->directory ?: $this->defaultStorePath();

        $this->name = $this->getStoreName($image);

        $this->executeCalls($image->getRealPath());

        $target = $this->uploadAndDeleteOriginal($image);

        return $target;
    }

    /**
     * @param $target
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

    protected function preview()
    {
        return '<img src="'.$this->objectUrl($this->value).'" class="file-preview-image">';
    }

    public function render()
    {
        $this->options(['allowedFileTypes' => ['image']]);

        return parent::render();
    }

    public function __call($method, $arguments)
    {
        $this->calls[] = [
            'method'    => $method,
            'arguments' => $arguments,
        ];

        return $this;
    }
}
