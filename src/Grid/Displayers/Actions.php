<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Actions extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var array
     */
    protected $prepends = [];

    /**
     * @var bool
     */
    protected $allowEdit = true;

    /**
     * @var bool
     */
    protected $allowDelete = true;

    /**
     * @var string
     */
    protected $resource;

    /**
     * Append a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function append($action)
    {
        array_push($this->appends, $action);

        return $this;
    }

    /**
     * Prepend a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function prepend($action)
    {
        array_unshift($this->prepends, $action);

        return $this;
    }

    /**
     * Disable delete.
     *
     * @return void.
     */
    public function disableDelete()
    {
        $this->allowDelete = false;
    }

    /**
     * Disable edit.
     *
     * @return void.
     */
    public function disableEdit()
    {
        $this->allowEdit = false;
    }

    /**
     * Set resource of current resource.
     *
     * @param $resource
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource ?: parent::getResource();
    }

    /**
     * {@inheritdoc}
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($this);
            call_user_func($callback, $this);
        }

        $actions = $this->prepends;
        if ($this->allowEdit) {
            array_push($actions, $this->editAction());
        }

        if ($this->allowDelete) {
            array_push($actions, $this->deleteAction());
        }

        $actions = array_merge($actions, $this->appends);

        return implode('', $actions);
    }

    /**
     * Built edit action.
     *
     * @return string
     */
    protected function editAction()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getKey()}/edit">
    <i class="fa fa-edit"></i>
</a>
EOT;
    }

    /**
     * Built delete action.
     *
     * @return string
     */
    protected function deleteAction()
    {
        $token = csrf_token();
        $confirm = trans('admin::lang.delete_confirm');
        $successMsg = trans('admin::lang.delete_succeeded');
        $faildMsg  = trans('admin::lang.delete_failed');

        $script = <<<SCRIPT

$('.grid-row-delete').click(function() {
    var id = $(this).data('id');
    if(confirm("{$confirm}")) {
        $.post('/{$this->getResource()}/' + id, {_method:'delete','_token':'{$token}'}, function(data){

            $.pjax.reload('#pjax-container');

            if (typeof data === 'object') {
                if (data.status) {
                    toastr.success('{$successMsg}');
                } else {
                    toastr.error('{$faildMsg}');
                }
            }
        });
    }
});

SCRIPT;

        Admin::script($script);

        return <<<EOT
<a href="javascript:void(0);" data-id="{$this->getKey()}" class="grid-row-delete">
    <i class="fa fa-trash"></i>
</a>
EOT;

    }
}
