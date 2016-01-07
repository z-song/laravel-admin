<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Administrator extends Model implements AuthenticatableContract
{
    use Authenticatable;
}
