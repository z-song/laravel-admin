<?php

namespace Encore\Admin\Grid\Actions;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;

class BatchDelete extends BatchAction
{
    public function name()
    {
        return trans('admin.batch_delete');
    }

    public function handle(Collection $collection)
    {
        try {
            $collection->each->delete();
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.delete_failed').$exception->getMessage());
        }

        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    public function dialog()
    {
        $this->confirm(trans('admin.delete_confirm'));
    }
}
