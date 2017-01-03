<?php

namespace Encore\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

class Tree implements Renderable
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
     * View of tree to render.
     *
     * @var string
     */
    protected $view = [
        'tree'      => 'admin::tree',
        'branch'    => 'admin::tree.branch',
    ];

    /**
     * @var \Closure
     */
    protected $branchCallback;

    /**
     * Menu constructor.
     *
     * @param Model|null $model
     */
    public function __construct(Model $model = null, \Closure $callback = null)
    {
        $this->model = $model;
        $this->branchCallback = $callback;

        $this->path = app('router')->current()->getPath();
        $this->elementId .= uniqid();
    }

    /**
     * Save tree order from a input.
     *
     * @param string $serialize
     *
     * @return bool
     */
    public function saveOrder($serialize)
    {
        $tree = json_decode($serialize, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        $this->model->saveOrder($tree);

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

        $('.menu-tools').on('click', function(e){
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });


SCRIPT;
    }

    /**
     * Set view of tree.
     *
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
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
        Admin::script($this->buildupScript());

        view()->share([
            'path'           => $this->path,
            'branchView'     => $this->view['branch'],
            'branchCallback' => $this->branchCallback,
        ]);

        return view($this->view['tree'], $this->variables())->render();
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
