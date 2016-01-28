<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    protected $rules = 'image';

    protected $directory = '';

    protected $name = null;

    protected $size = [];

    protected $thumb = [];

    public function size($width, $height)
    {
        $this->size = ['width' => $width, 'height' => $height];

        return $this;
    }

    public function thumb($width, $height)
    {
        $this->thumb[] = ['width' => $width, 'height' => $height];

        return $this;
    }

    public function preview($width = null, $height = null)
    {
        if ( ! file_exists($this->value)) return '';

        return '<br><img src="' . ImageManagerStatic::make($this->value)->encode('data-url').'" class="pull-left img-responsive">';

    }

    public function prepare(UploadedFile $image = null)
    {
        if(is_null($image)) return $this->original;

        $this->directory = $this->directory ?
            $this->directory : config('admin.upload.image');

        $this->name = $this->name ? $this->name : md5(uniqid());

        $target = $image->move($this->directory, $this->name);

        $this->destroy();

        if( ! empty($this->size))
        {
            $image = ImageManagerStatic::make($target);
            $image->resize($this->size['width'], $this->size['height'])->save($target);
        }

        return $target;
    }

    public function render()
    {
        return parent::render()->with(['preview' => $this->preview()]);
    }
}