<?php

namespace Encore\Admin;

use Encore\Admin\Facades\Admin as AdminManager;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tree
{
    protected $items = [];

    protected $script;

    protected $elementId = 'tree-';

    protected $model;

    public function __construct(Model $model = null)
    {
        $this->model = $model;

        $this->path = app('router')->current()->getPath();
        $this->elementId .= uniqid();
    }

    public function buildItems()
    {
        if ($this->model) {
            $this->items = $this->model->toTree();
        }
    }

    public function variables()
    {
        $this->buildItems();

        return [
            'items' => $this->items,
            'id'    => $this->elementId,
        ];
    }

    protected function buildupScript()
    {
        $confirm = trans('admin::lang.delete_confirm');
        $token = csrf_token();

        $this->script = <<<SCRIPT

        $('#{$this->elementId}').nestable({});

        $('._delete').click(function() {
            var id = $(this).data('id');
            if(confirm("{$confirm}")) {
                $.post('/{$this->path}/' + id, {_method:'delete','_token':'{$token}'}, function(data){
                    $.pjax.reload('#pjax-container');
                });
            }
        });

        $('.{$this->elementId}-save').click(function () {
            var serialize = $('#{$this->elementId}').nestable('serialize');
            $.get('/{$this->path}', {'_tree':JSON.stringify(serialize)}, function(data){
                $.pjax.reload('#pjax-container');
            });
        });

        $('.{$this->elementId}-refresh').click(function () {
            $.pjax.reload('#pjax-container');
        });


SCRIPT;
    }

    public function render()
    {
        if (Request::capture()->has('_tree')) {
            return response()->json([
                'status' => $this->buildTree(Request::capture()->get('_tree')),
            ]);
        }

        $this->buildupScript();

        AdminManager::script($this->script);

        view()->share(['path'  => $this->path]);

        return view('admin::tree', $this->variables())->render();
    }

    public function buildTree($serialize)
    {
        $tree = json_decode($serialize, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        $this->model->buildTree($tree);

        return true;
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
