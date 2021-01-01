<?php

namespace Encore\Admin\Table\Actions;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;

class BatchDelete extends BatchAction
{
    /**
     * @var string
     */
    protected $method = 'DELETE';

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function name()
    {
        return trans('admin.batch_delete');
    }

    /**
     * @param Collection $collection
     *
     * @return \Encore\Admin\Actions\Response
     */
    public function handle(Collection $collection)
    {
        try {
            $collection->each->delete();
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.delete_failed').$exception->getMessage());
        }

        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    /**
     * {@inheritdoc}
     */
    public function dialog()
    {
        $this->confirm(trans('admin.delete_confirm'));
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return $this->parent->resource().'/_batch_delete';
    }
}
