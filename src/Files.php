<?php
namespace Encore\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Files
{

	/**
	 * Storage instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $storage = '';

	/**
	 * File save path.
	 *
	 * @var string
	 */
	protected $directory;

	/**
	 * Complete file path with file name.
	 *
	 * @var string
	 */
	protected $target;

	/**
	 * File name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * If use unique name to store upload file.
	 *
	 * @var bool
	 */
	protected $useUniqueName = true;

	protected $attributes = [];

	/**
	 * Files constructor.
	 *
	 * @param $attributes
	 */
	public function __construct($attributes = [])
	{

		$this->initStorage();

		$this->attributes($attributes);

	}

	public function upload(UploadedFile $file)
	{

		$target = $this->getDirectory().$this->getName($file);

		$this->attributes([
			'original_name' => $file->getClientOriginalName(),
			'target'        => $target,
		]);

		$this->storage->put($target, file_get_contents($file->getRealPath()));

		return $this->attributes();
	}

	public function destroy($target)
	{

		if ($this->storage->exists($target)) {
			$this->storage->delete($target);
		}

		return $this;
	}


	/**
	 * Get directory for store file.
	 *
	 * @return mixed|string
	 */
	public function getDirectory()
	{
		return $this->directory ?: $this->defaultDirectory();
	}

	/**
	 * Default directory for file to upload.
	 *
	 * @return mixed
	 */
	protected function defaultDirectory()
	{
		return config('admin.upload.directory.file').'/'.date('Y-m-d').'/';
	}

	/**
	 * Get file name.
	 *
	 * @return mixed
	 * author Edwin Hui
	 */
	protected function getName(UploadedFile $file)
	{
		$this->name = $this->useUniqueName ? $this->generateUniqueName($file) : $file->getClientOriginalName();

		return $this->name;
	}


	/**
	 * Set file name in unique.
	 *
	 * @param $bool
	 * @return $this
	 * author Edwin Hui
	 */
	public function uniqueName($bool)
	{
		if($bool === false){
			$this->useUniqueName = false;

			return $this;
		}

		$this->useUniqueName = true;

		return $this;
	}

	/**
	 * Set disk for storage.
	 *
	 * @param string $disk Disks defined in `config/filesystems.php`.
	 *
	 * @return $this
	 */
	public function disk($disk)
	{
		if (!array_key_exists($disk, config('filesystems.disks'))) {
			$error = new MessageBag([
				'title'   => 'Config error.',
				'message' => "Disk [$disk] not configured, please add a disk config in `config/filesystems.php`.",
			]);

			return session()->flash('error', $error);
		}

		$this->storage = Storage::disk($disk);

		return $this;
	}

	/**
	 * Specify the directory and name for upload file.
	 *
	 * @param string $directory
	 * @param null|string $name
	 *
	 * @return $this
	 */
	public function move($directory, $name = null)
	{
		$this->dir($directory);

		$this->name($name);

		return $this;
	}

	/**
	 * Specify the directory upload file.
	 *
	 * @param string $dir
	 *
	 * @return $this
	 */
	public function dir($dir)
	{
		if ($dir) {
			$this->directory = $dir;

			return $this;
		}

		return $this->directory;
	}

	/**
	 * Set name of store name.
	 *
	 * @param string|callable $name
	 *
	 * @return $this
	 */
	public function name($name)
	{
		if ($name) {
			$this->name = $name;

			return $this;
		}

		return $this->name;

	}

	/**
	 * Generate a unique name for uploaded file.
	 *
	 * @return string
	 */
	protected function generateUniqueName(UploadedFile $file)
	{
		return md5(uniqid()).'.'.$file->getClientOriginalExtension();
	}

	/**
	 * Initialize the storage instance.
	 *
	 * @return void.
	 */
	protected function initStorage()
	{
		$this->disk(config('admin.upload.disk'));
	}

	/**
	 * @param $attributes
	 * @return $this|$this->attributes
	 * author Edwin Hui
	 */
	protected function attributes($attributes = null)
	{
		if(is_array($attributes)){
			$this->attributes = array_merge($this->attributes, (array) $attributes);

			return $this;
		}

		return $this->attributes;
	}
}