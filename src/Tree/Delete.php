<?php

namespace Encore\Admin\Tree;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class Delete extends Action
{
    /**
     * @var string
     */
    protected $selector = '.tree_branch_delete';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $method = 'DELETE';

    /**
     * @var array
     */
    protected $key;

    /**
     * @var string
     */
    protected $model;

    /**
     * Delete constructor.
     *
     * @param string $path
     */
    public function __construct($path = '', $key = null)
    {
        $this->path = $path;
        $this->key = $key;

        parent::__construct();
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function handle(Request $request)
    {
        $model = $request->get('model');

        try {
            $model::find($request->get('key'))->delete();
        } catch (\Exception $exception) {
            return $this->response()->error(trans('admin.delete_failed')." : {$exception->getMessage()}");
        }

        return $this->response()->success(trans('admin.delete_succeeded'))->refresh();
    }

    public function parameters()
    {
        return ['model' => $this->model];
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
        return '';
    }

    public function html()
    {
        $url = $this->path.'/'.$this->key;

        $text = trans('admin.delete');

        return <<<HTML
<a href="javascript:void(0);" data-key="{$this->key}" class="tree_branch_delete" url="{$url}">
    <i class="fa fa-trash"></i>
</a>
HTML;
    }
}
