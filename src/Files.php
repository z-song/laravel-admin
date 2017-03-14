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

	/**
	 * Determines the mimetype of a file by looking at its extension.
	 *
	 * @param $filename
	 *
	 * @return null|string
	 */
	function mimetype_from_filename($filename)
	{
		return $this->mimetype_from_extension(pathinfo($filename, PATHINFO_EXTENSION));
	}

	/**
	 * Maps a file extensions to a mimetype.
	 *
	 * @param $extension string The file extension.
	 *
	 * @return string|null
	 * @link http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types
	 */
	function mimetype_from_extension($extension)
	{
		static $mimetypes = [
			'7z' => 'application/x-7z-compressed',
			'aac' => 'audio/x-aac',
			'ai' => 'application/postscript',
			'aif' => 'audio/x-aiff',
			'asc' => 'text/plain',
			'asf' => 'video/x-ms-asf',
			'atom' => 'application/atom+xml',
			'avi' => 'video/x-msvideo',
			'bmp' => 'image/bmp',
			'bz2' => 'application/x-bzip2',
			'cer' => 'application/pkix-cert',
			'crl' => 'application/pkix-crl',
			'crt' => 'application/x-x509-ca-cert',
			'css' => 'text/css',
			'csv' => 'text/csv',
			'cu' => 'application/cu-seeme',
			'deb' => 'application/x-debian-package',
			'doc' => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'dvi' => 'application/x-dvi',
			'eot' => 'application/vnd.ms-fontobject',
			'eps' => 'application/postscript',
			'epub' => 'application/epub+zip',
			'etx' => 'text/x-setext',
			'flac' => 'audio/flac',
			'flv' => 'video/x-flv',
			'gif' => 'image/gif',
			'gz' => 'application/gzip',
			'htm' => 'text/html',
			'html' => 'text/html',
			'ico' => 'image/x-icon',
			'ics' => 'text/calendar',
			'ini' => 'text/plain',
			'iso' => 'application/x-iso9660-image',
			'jar' => 'application/java-archive',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'js' => 'text/javascript',
			'json' => 'application/json',
			'latex' => 'application/x-latex',
			'log' => 'text/plain',
			'm4a' => 'audio/mp4',
			'm4v' => 'video/mp4',
			'mid' => 'audio/midi',
			'midi' => 'audio/midi',
			'mov' => 'video/quicktime',
			'mp3' => 'audio/mpeg',
			'mp4' => 'video/mp4',
			'mp4a' => 'audio/mp4',
			'mp4v' => 'video/mp4',
			'mpe' => 'video/mpeg',
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'mpg4' => 'video/mp4',
			'oga' => 'audio/ogg',
			'ogg' => 'audio/ogg',
			'ogv' => 'video/ogg',
			'ogx' => 'application/ogg',
			'pbm' => 'image/x-portable-bitmap',
			'pdf' => 'application/pdf',
			'pgm' => 'image/x-portable-graymap',
			'png' => 'image/png',
			'pnm' => 'image/x-portable-anymap',
			'ppm' => 'image/x-portable-pixmap',
			'ppt' => 'application/vnd.ms-powerpoint',
			'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'ps' => 'application/postscript',
			'qt' => 'video/quicktime',
			'rar' => 'application/x-rar-compressed',
			'ras' => 'image/x-cmu-raster',
			'rss' => 'application/rss+xml',
			'rtf' => 'application/rtf',
			'sgm' => 'text/sgml',
			'sgml' => 'text/sgml',
			'svg' => 'image/svg+xml',
			'swf' => 'application/x-shockwave-flash',
			'tar' => 'application/x-tar',
			'tif' => 'image/tiff',
			'tiff' => 'image/tiff',
			'torrent' => 'application/x-bittorrent',
			'ttf' => 'application/x-font-ttf',
			'txt' => 'text/plain',
			'wav' => 'audio/x-wav',
			'webm' => 'video/webm',
			'wma' => 'audio/x-ms-wma',
			'wmv' => 'video/x-ms-wmv',
			'woff' => 'application/x-font-woff',
			'wsdl' => 'application/wsdl+xml',
			'xbm' => 'image/x-xbitmap',
			'xls' => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xml' => 'application/xml',
			'xpm' => 'image/x-xpixmap',
			'xwd' => 'image/x-xwindowdump',
			'yaml' => 'text/yaml',
			'yml' => 'text/yaml',
			'zip' => 'application/zip',
		];

		$extension = strtolower($extension);

		return isset($mimetypes[$extension])
			? $mimetypes[$extension]
			: null;
	}
}