<?php

namespace Encore\Admin\Menu;

use Encore\Admin\Facades\Admin as AdminManager;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

class Menu implements Renderable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var string
     */
    protected $elementId = 'tree-';

    /**
     * @var Model
     */
    protected $model;

    /**
     * Menu constructor.
     *
     * @param Model|null $model
     */
    public function __construct(Model $model = null)
    {
        $this->model = $model;

        $this->path = app('router')->current()->getPath();
        $this->elementId .= uniqid();
    }

    /**
     * Build menu tree presented by array.
     *
     * @param string $serialize
     *
     * @return bool
     */
    public function saveTree($serialize)
    {
        $tree = json_decode($serialize, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        $this->model->saveTree($tree);

        return true;
    }

    /**
     * Build tree grid scripts.
     *
     * @return string
     */
    protected function buildupScript()
    {
        $token = csrf_token();

        $confirm = trans('admin::lang.delete_confirm');
        $saveSucceeded = trans('admin::lang.save_succeeded');
        $refreshSucceeded = trans('admin::lang.refresh_succeeded');
        $deleteSucceeded = trans('admin::lang.delete_succeeded');

        return <<<SCRIPT

        $('#{$this->elementId}').nestable({});

        $('._delete').click(function() {
            var id = $(this).data('id');
            if(confirm("{$confirm}")) {
                $.post('/{$this->path}/' + id, {_method:'delete','_token':'{$token}'}, function(data){
                    $.pjax.reload('#pjax-container');
                    toastr.success('{$deleteSucceeded}');
                });
            }
        });

        $('.{$this->elementId}-save').click(function () {
            var serialize = $('#{$this->elementId}').nestable('serialize');

            $.post('/{$this->path}', {
                _token: '{$token}',
                _order: JSON.stringify(serialize)
            },
            function(data){
                $.pjax.reload('#pjax-container');
                toastr.success('{$saveSucceeded}');
            });
        });

        $('.{$this->elementId}-refresh').click(function () {
            $.pjax.reload('#pjax-container');
            toastr.success('{$refreshSucceeded}');
        });


SCRIPT;
    }

    /**
     * Variables in tree template.
     *
     * @return array
     */
    public function variables()
    {
        return [
            'id'    => $this->elementId,
            'items' => $this->model->toTree(),
        ];
    }

    /**
     * Render a tree.
     *
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function render()
    {
        AdminManager::script($this->buildupScript());

        view()->share(['path'  => $this->path]);

        return view('admin::menu.tree', $this->variables())->render();
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
