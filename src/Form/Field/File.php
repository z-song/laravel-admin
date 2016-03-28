<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Field
{
    protected $directory = '';

    protected $name = null;

    public function move($directory, $name = null)
    {
        $this->directory = $directory;

        $this->name = $name;

        return $this;
    }

    public function prepare(UploadedFile $file = null)
    {
        if (is_null($file)) {
            return $this->original;
        }

        $this->directory = $this->directory ?
            $this->directory : config('admin.upload.file');

        $this->name = $this->name ? $this->name : md5(uniqid());

        $target = $file->move($this->directory, $this->name);

        return $target;
    }

    public function destroy()
    {
        @unlink($this->original);
    }
}
