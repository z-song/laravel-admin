<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use App\Models\AdminFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultipleFile extends Field
{
    use UploadField;

    /**
     * Css.
     *
     * @var array
     */
    protected static $css = [
        '/packages/admin/bootstrap-fileinput/css/fileinput.min.css?v=4.3.7',
    ];

    /**
     * Js.
     *
     * @var array
     */
    protected static $js = [
        '/packages/admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js?v=4.3.7',
        '/packages/admin/bootstrap-fileinput/js/fileinput.min.js?v=4.3.7',
    ];



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
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return false;
        }

        $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $attributes[$this->column] = $this->label;

        list($rules, $input) = $this->hydrateFiles(array_get($input, $this->column, []));

        return Validator::make($input, $rules, [], $attributes);
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
        $rules = $input = [];

        foreach ($value as $key => $file) {
            $rules[$this->column.$key] = $this->getRules();
            $input[$this->column.$key] = $file;
        }

        return [$rules, $input];
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
            return $this->destroy(request(static::FILE_DELETE_FLAG));
        }

        $datas = array_map([$this, 'prepareForeach'], $files);

        try{
            AdminFiles::insert($datas);
        }
        catch(\Exception $e){
            return $e->getMessage();
        }

        return [];
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

        return $this->upload($file);
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return array
     */
    protected function preview()
    {
        $files = $this->value ?: [];

        return array_map(function($file){

            return $this->objectUrl(array_get($file, 'target'));

        }, $files);
    }


    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $files = $this->value ?: [];

        $config = [];

        foreach ($files as $index => $file) {

            $originalName = array_get($file, 'original_name');

            $target = public_path('upload').'/'.array_get($file, 'target');

            $type = file_exists($target) ? mime_content_type($target) : 'object';

            $config[] = [
                'caption' => $originalName,
                'key'     => array_get($file, config('admin.upload.table.key')),
                'filetype'    => $type
            ];
        }

        return $config;
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
            $this->setupPreviewOptions();
        }

        $options = json_encode($this->options);

        $this->script = <<<EOT
$("input{$this->getElementClassSelector()}").fileinput({$options});
EOT;

        return parent::render();
    }

    /**
     * Destroy original files.
     *
     * @return string.
     */
    public function destroy($key)
    {
        $tableName = config('admin.upload.table.name');

        $table = DB::table($tableName);

        $fileInfo = $table->find($key);

        $target = public_path('upload').'/'.$fileInfo->target;

        try{
            $table->delete($key);
        }
        catch(\Exception $e){
            return $e->getMessage();
        }

        if(file_exists($target)){
            unlink($target);
        }

        return [];
    }
}
