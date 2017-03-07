<?php

namespace Encore\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminFiles extends Model
{
	protected $table = 'admin_files';

	public static function getKeyString()
	{
		return with(new static)->getKeyName();
	}

	public function scopePurchorderFiles($query)
	{
		return $query->where('table', PurchOrder::getTableName());
	}

	public function scopeProductFiles($query)
	{
		return $query->where('table', Product::getTableName());
	}
}
