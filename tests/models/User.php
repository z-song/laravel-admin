<?php
/**
 * Created by PhpStorm.
 * User: song
 * Email: zousong@yiban.cn
 * Date: 16/11/22
 * Time: 下午1:47
 */

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'test_users';

    protected $appends = ['full_name', 'position'];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->profile->first_name} {$this->profile->last_name}";
    }

    public function getPositionAttribute()
    {
        return "{$this->profile->latitude} {$this->profile->longitude}";
    }
}
