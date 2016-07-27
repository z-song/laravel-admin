<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Field
{
    const ACTION_KEEP   = 0;
    const ACTION_REMOVE = 1;

    protected $directory = '';

    protected $name = null;

    protected $options = [];

    public function __construct($column, $arguments = [])
    {
        $this->initOptions();

        parent::__construct($column, $arguments);
    }

    protected function initOptions()
    {
        $this->options = [
            'overwriteInitial'  => true,
            'showUpload'        => false,
            'language'          => config('app.locale')
        ];
    }

    public function move($directory, $name = null)
    {
        $this->directory = $directory;

        $this->name = $name;

        return $this;
    }

    public function prepare(UploadedFile $file = null)
    {
        if (is_null($file)) {
            if ($this->isDeleteRequest()) {
                return '';
            }

            return $this->original;
        }

        $this->directory = $this->directory ?
            $this->directory : config('admin.upload.file');

        $this->name = $this->name ? $this->name : $file->getClientOriginalName();

        $target = $this->uploadAndDeleteOriginal($file);

        return trim(str_replace(public_path(), '', $target->__toString()), '/');
    }

    /**
     * @param $file
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file)
    {
        $this->renameIfExists($file);

        $target = $file->move($this->directory, $this->name);

        $this->destroy();

        return $target;
    }

    protected function preview()
    {
        $fileName = basename($this->value);

        return <<<EOT
<div class="file-preview-other-frame">
   <div class="file-preview-other">
   <span class="file-icon-4x"><i class="glyphicon glyphicon-file"></i></span>
</div>
   </div>
   <div class="file-preview-other-footer"><div class="file-thumbnail-footer">
    <div class="file-footer-caption" title="realm_demo.realm">{$fileName}</div>
</div></div>
EOT;
    }

    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function render()
    {
        $this->js[] = 'bootstrap-fileinput/js/fileinput_locale_' . config('app.locale') . '.js';

        $this->options['initialCaption'] = basename($this->value);

        if (! empty($this->value)) {
            $this->options['initialPreview'] = $this->preview();
        }

        $options = json_encode($this->options);

        $this->script = <<<EOT

$("#{$this->id}").fileinput({$options});

$("#{$this->id}").on('filecleared', function(event) {
    $("#{$this->id}_action").val(1);
});

EOT;
        return parent::render();
    }

    /**
     * If is delete request then delete original image.
     *
     * @return bool
     */
    public function isDeleteRequest()
    {
        $action = Input::get($this->id . '_action');

        if ($action == static::ACTION_REMOVE) {
            $this->destroy();

            return true;
        }

        return false;
    }

    /**
     * @param $file
     * @return void
     */
    public function renameIfExists(UploadedFile $file)
    {
        if (file_exists("$this->directory/$this->name")) {
            $this->name = md5(uniqid()) . '.' . $file->guessExtension();
        }
    }

    public function destroy()
    {
        @unlink($this->original);
    }
}
