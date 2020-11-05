<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Factories\TagFactory;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'test_tags';

	protected static function newFactory()
	{
		return TagFactory::new();
	}

    public function users()
    {
        return $this->belongsToMany(User::class, 'test_user_tags', 'tag_id', 'user_id');
    }
}
