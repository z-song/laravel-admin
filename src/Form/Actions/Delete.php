<?php

namespace Encore\Admin\Form\Actions;

use Illuminate\Http\Request;

class Delete extends Action
{
    protected $selector = '.delete-record';

    protected $path;

    protected $method = 'DELETE';

    public function __construct($path = '')
    {
        $this->path = $path;

        parent::__construct();
    }

    public function handle(Request $request)
    {
        $key = $request->get('key');
        $model = $request->get('model');
        $path = $request->get('path');

        $trans = [
            'failed'    => trans('admin.delete_failed'),
            'succeeded' => trans('admin.delete_succeeded'),
        ];

        try {
            $model = $model::find($key)->delete();
        } catch (\Exception $exception) {
            return $this->response()->error("{$trans['failed']} : {$exception->getMessage()}");
        }

        return $this->response()->success($trans['succeeded'])->redirect($path);
    }

    public function parameters()
    {
        return [
            'model' => get_class($this->model),
            'key'   => $this->model->getKey(),
            'path'  => $this->path,
        ];
    }

    public function dialog()
    {
        $this->confirm(trans('admin.delete_confirm'));
    }

    /**
     * @return string
     */
    public function getHandleUrl()
    {
        return $this->path.'/'.$this->model->getKey();
    }

    public function html()
    {
        $text = trans('admin.delete');

        return <<<HTML
<div class="btn-group float-right" style="margin-right: 5px">
    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-record" title="{$text}">
        <i class="fa fa-trash"></i><span class="hidden-xs">  {$text}</span>
    </a>
</div>
HTML;
    }
}
