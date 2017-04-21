<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class AdminFiles.
 *
 */
class Files extends Model
{

	/**
	 * Storage instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $storage = '';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['parent_id', 'table', 'original_name', 'target'];

	/**
	 * Create a new Eloquent model instance.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		$connection = config('admin.database.connection') ?: config('database.default');

		$this->setConnection($connection);

		$this->setTable(config('admin.database.files_table'));

		$this->initStorage();

		parent::__construct($attributes);
	}

	/**
	 * Scope table files.
	 *
	 * @param $query
	 * @param array $attributes
	 * @return mixed
	 * author Edwin Hui
	 */
	public function scopeMultiWhere($query, $attributes = [])
	{
		foreach($attributes as $key=>$attr){

			$query = $query->where($key, $attr);
		}

		return $query;
	}

	public function scopeGetFiles($query, $table = null)
	{
		if(is_array($table)){
			return $this->scopeMultiWhere($query, $table);
		}

		return $this->scopeMultiWhere($query, ['table' => $table]);
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
	 * Initialize the storage instance.
	 *
	 * @return void.
	 */
	protected function initStorage()
	{
		$this->disk(config('admin.upload.disk'));
	}

//	protected function finishSave(array $options)
//	{
//		parent::finishSave($options);
//	}
}
