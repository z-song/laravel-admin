<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Authorizable
 *
 * @mixin Action
 */
trait Authorizable
{
    /**
     * @param Model $model
     *
     * @return bool
     */
    public function passesAuthorization($model = null)
    {
        if (!method_exists($this, 'authorize')) {
            return true;
        }

        return $this->authorize(Admin::user(), $model) === true;
    }

    /**
     * @return mixed
     */
    public function failedAuthorization()
    {
        return $this->response()->error(__('admin.deny'))->send();
    }
}
