<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Field
{
    const ACTION_KEEP   = 0;
    const ACTION_REMOVE = 1;

    protected $js = [
        'bootstrap-fileinput/js/plugins/canvas-to-blob.min.js',
        'bootstrap-fileinput/js/fileinput.min.js'
    ];

    protected $css = [
        'bootstrap-fileinput/css/fileinput.min.css'
    ];

    protected $directory = '';

    protected $name = null;

    protected $options = [];

    public function __construct($column, $arguments = [])
    {
        $this->initOptions();

        parent::__construct($column, $arguments);
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

            $action = Input::get($this->id . '_action');

            if ($action == static::ACTION_REMOVE) {
                $this->destroy();

                return '';
            }

            return $this->original;
        }

        $this->directory = $this->directory ?
            $this->directory : config('admin.upload.file');

        $this->name = $this->name ? $this->name : $file->getClientOriginalName();

        $target = $file->move($this->directory, $this->name);

        $this->destroy();

        return trim(str_replace(public_path(), '', $target->__toString()), '/');
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

    protected function initOptions()
    {
        $this->options = [
            'overwriteInitial'  => true,
            'showUpload'        => false,
            'language'          => config('app.locale')
        ];
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

    public function destroy()
    {
        @unlink($this->original);
    }
}
