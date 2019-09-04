<?php

namespace Encore\Admin\Actions;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;

/**
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
        if (!method_exists($model, 'authorize')) {
            return true;
        }

        return $model->authorize(Admin::user()) === true;
    }

    /**
     * @return mixed
     */
    public function failedAuthorization()
    {
        return $this->response()->error(__('admin.deny'))->send();
    }
}
