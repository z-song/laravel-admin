<?php

namespace Encore\Admin\Form\Field;

class MultipleFile2 extends File2
{
	public function __construct($column, array $arguments)
	{
		$this->attribute(['multiple' => true]);

		$this->options([
			'overwriteInitial' => false
		]);

		parent::__construct($column, $arguments);
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
		return array_map([$this, 'prepareEach'], $files);
	}
}