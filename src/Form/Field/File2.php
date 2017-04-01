<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Encore\Admin\Files as FilesControl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL as FacadesUrl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class File2 extends Field
{

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


	protected $filesController;

	protected $extraAttributes;

	protected $view = 'admin::form.multiplefile';


	/**
	 * {@inheritdoc}
	 */
	public function getValidator(array $input)
	{
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
			$rules[$this->column . $key] = $this->getRules();
			$input[$this->column . $key] = $file;
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
	public function saving($files, $key = null)
	{
		$files = $key ? $files[$key] : $files;

		$original = $this->original();

		foreach($original as $k => $ori){
			$original[$k][Form::REMOVE_FLAG_NAME] = 1;
		}

		return array_map([$this, 'prepareEach'], array_merge($original, $files));
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
	 * Preview html for file-upload plugin.
	 *
	 * @return array
	 */
	protected function preview()
	{
		$preview = [];

		foreach((array) $this->value() as $file){

			$preview[] = $this->uploadFileUrl(array_get($file,'target'));
		}

		return $preview;
	}

	/**
	 * Get file visit url.
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function uploadFileUrl($path)
	{
		if (FacadesUrl::isValidUrl($path)) {
			return $path;
		}

		return rtrim(config('admin.upload.host'), '/') . '/' . trim($path, '/');
	}

	/**
	 * @return array initialPreviewConfig
	 */
	protected function initialPreviewConfig()
	{
		$files = $this->value() ?: [];

		$delFlag = Form::REMOVE_FLAG_NAME;

		$config = [];

		foreach ($files as $index => $file) {

//			$type = $this->getFiesController()->mimetype_from_filename(array_get($file, 'target'));

			$id = array_get($file, 'id');

			$config[] = [
				'caption' => array_get($file, 'original_name'),
//				'filetype'    => $type,
				'key'       => $id,
				'extra'     => [
					"{$this->column()}[{$id}][id]" => $id,
					"{$this->column()}[{$id}][target]" => array_get($file, 'target'),
					"{$this->column()}[{$id}][{$delFlag}]" => 1,
					'_token'            => csrf_token(),
					'_method'           => 'PUT'
				]
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
		if (!empty($this->value())) {
			$this->setupPreviewOptions();
		}

		$this->setupDefaultOptions();

		$options = json_encode($this->options);

		$this->script = <<<EOT
$("input{$this->getElementClassSelector()}").fileinput({$options});
EOT;

		return parent::render();
	}


	protected function setupDefaultOptions()
	{

		$this->options = array_merge([
			'overwriteInitial'     => true,
			'initialPreviewAsData' => true,
			'initialPreviewFileType' => 'object',
			'browseLabel'          => trans('admin::lang.browse'),
			'showRemove'           => false,
			'showUpload'           => false,
			'initialCaption'   => $this->initialCaption( $this->value() ),
			'deleteUrl'        => $this->form->resource() . '/'. $this->form->model()->getKey()

		], (array) $this->options);
	}

	public function setupPreviewOptions()
	{
		$this->options([
			'initialPreview'        => $this->preview(),
			'initialPreviewConfig'  => $this->initialPreviewConfig(),
		]);
	}

	protected function initialCaption($files)
	{
		$caption = [];

		foreach($files as $file){
			$caption[] = array_get($file, 'original_name');
		}

		return implode($caption, ',');
	}

	/**
	 * Prepare for each file.
	 *
	 * @param \Illuminate\Http\UploadedFile|array $file
	 *
	 * @return mixed|string
	 */
	protected function prepareEach($file)
	{
		if(array_get($file, Form::REMOVE_FLAG_NAME) == 1){
			$this->getFiesController()->destroy( array_get($file, 'target'));

			return $file;
		}

		$uploaded = $this->getFiesController()->upload($file);

		$extra = [];

		if($this->extraAttributes instanceof \Closure){
			$extra = call_user_func($this->extraAttributes, $file);
		}

		return array_merge((array) $uploaded, (array) $extra);
	}

	/**
	 * Extra table data.
	 *
	 * @param \Closure $callback
	 */
	public function extraAttributes(\Closure $callback)
	{
		$this->extraAttributes = $callback->bindTo($this);
	}

	protected function getFiesController()
	{
		if(!$this->filesController){

			$this->filesController = new FilesControl();
		}

		return $this->filesController;
	}

}