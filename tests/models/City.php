<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'test_cities';

    public function areas()
    {
        return $this->hasMany(Area::class, 'city_id');
    }
}
