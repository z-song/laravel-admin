<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Factories\UserFactory;

class User extends Model
{
    use HasFactory;

    protected $table = 'test_users';

    protected $appends = ['full_name', 'position'];

    protected $casts = ['data' => 'array'];

	protected static function newFactory()
	{
		return UserFactory::new();
	}

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->profile['first_name']} {$this->profile['last_name']}";
    }

    public function getPositionAttribute()
    {
        return "{$this->profile->latitude} {$this->profile->longitude}";
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'test_user_tags', 'user_id', 'tag_id');
    }
}
