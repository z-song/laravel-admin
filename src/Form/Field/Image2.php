<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Files as FilesControl;

class Image2 extends File2
{
	public function __construct($column, array $arguments)
	{
		$this->options([
			'allowedFileExtensions' => ["jpg", "png", "gif"]
		]);

		parent::__construct($column, $arguments);
	}


	protected function getFiesController()
	{
		if(!$this->filesController){

			$this->filesController = (new FilesControl())->dir(config('admin.upload.directory.image').'/'.date('Y-m-d').'/');
		}

		return $this->filesController;
	}
}