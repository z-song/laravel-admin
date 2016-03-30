<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    protected $rules = 'image';

    protected $size = [];

    public function size($width, $height)
    {
        $this->size = ['width' => $width, 'height' => $height];

        return $this;
    }

    public function prepare(UploadedFile $image = null)
    {
        if (is_null($image)) {

            $action = Input::get($this->id . '_action');

            if ($action == static::ACTION_REMOVE) {
                $this->destroy();

                return '';
            }

            return $this->original;
        }

        $this->directory = $this->directory ?
            $this->directory : config('admin.upload.image');

        $this->name = $this->name ? $this->name : $image->getClientOriginalName();

        $target = $image->move($this->directory, $this->name);

        $this->destroy();

        if (! empty($this->size)) {
            $image = ImageManagerStatic::make($target);
            $image->resize($this->size['width'], $this->size['height'])->save($target);
        }

        return trim(str_replace(public_path(), '', $target->__toString()), '/');
    }

    protected function preview()
    {
        return '<img src="/' . $this->value . '" class="file-preview-image">';
    }

    public function render()
    {
        $this->options(['allowedFileTypes' => ['image']]);

        return parent::render();
    }
}
