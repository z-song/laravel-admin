<?php

namespace Encore\Admin\Show;

use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * The panel that holds this tool.
     *
     * @var Panel
     */
    protected $panel;

    /**
     * @var string
     */
    protected $resource;

    /**
     * Default tools.
     *
     * @var array
     */
    protected $tools = ['delete', 'edit', 'list'];

    /**
     * Tools should be appends to default tools.
     *
     * @var Collection
     */
    protected $appends;

    /**
     * Tools should be prepends to default tools.
     *
     * @var Collection
     */
    protected $prepends;

    /**
     * Tools constructor.
     *
     * @param Panel $panel
     */
    public function __construct(Panel $panel)
    {
        $this->panel = $panel;

        $this->appends = new Collection();
        $this->prepends = new Collection();
    }

    /**
     * Append a tools.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function append($tool)
    {
        $this->appends->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function prepend($tool)
    {
        $this->prepends->push($tool);

        return $this;
    }

    /**
     * Get resource path.
     *
     * @return string
     */
    public function getResource()
    {
        if (is_null($this->resource)) {
            $this->resource = $this->panel->getParent()->getResourcePath();
        }

        return $this->resource;
    }

    /**
     * Disable `list` tool.
     *
     * @return $this
     */
    public function disableList()
    {
        array_delete($this->tools, 'list');

        return $this;
    }

    /**
     * Disable `delete` tool.
     *
     * @return $this
     */
    public function disableDelete()
    {
        array_delete($this->tools, 'delete');

        return $this;
    }

    /**
     * Disable `edit` tool.
     *
     * @return $this
     */
    public function disableEdit()
    {
        array_delete($this->tools, 'edit');

        return $this;
    }

    /**
     * Get request path for resource list.
     *
     * @return string
     */
    protected function getListPath()
    {
        return '/'.ltrim($this->getResource(), '/');
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getEditPath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath().'/'.$key.'/edit';
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath().'/'.$key;
    }

    /**
     * Render `list` tool.
     *
     * @return string
     */
    protected function renderList()
    {
        $list = trans('admin.list');

        return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{$this->getListPath()}" class="btn btn-sm btn-default">
        <i class="fa fa-list"></i> {$list}
    </a>
</div>
HTML;
    }

    /**
     * Render `edit` tool.
     *
     * @return string
     */
    protected function renderEdit()
    {
        $edit = trans('admin.edit');

        return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{$this->getEditPath()}" class="btn btn-sm btn-primary">
        <i class="fa fa-edit"></i> {$edit}
    </a>
</div>
HTML;
    }

    /**
     * Render `delete` tool.
     *
     * @return string
     */
    protected function renderDelete()
    {
        $deleteConfirm = trans('admin.delete_confirm');
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        $class = uniqid();

        $script = <<<SCRIPT

$('.{$class}-delete').unbind('click').click(function() {

    swal({
      title: "$deleteConfirm",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "$confirm",
      closeOnConfirm: false,
      cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'post',
            url: '{$this->getDeletePath()}',
            data: {
                _method:'delete',
                _token:LA.token,
            },
            success: function (data) {
                $.pjax({container:'#pjax-container', url: '{$this->getListPath()}' });

                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

SCRIPT;

        $delete = trans('admin.delete');

        Admin::script($script);

        return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="javascript:void(0);" class="btn btn-sm btn-danger {$class}-delete">
        <i class="fa fa-trash"></i>  {$delete}
    </a>
</div>
HTML;
    }

    /**
     * Render custom tools.
     *
     * @param Collection $tools
     * @return mixed
     */
    protected function renderCustomTools($tools)
    {
        return $tools->map(function ($tool) {
            if ($tool instanceof Renderable) {
                return $tool->render();
            }

            if ($tool instanceof Htmlable) {
                return $tool->toHtml();
            }

            return (string) $tool;
        })->implode(' ');
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        $output = $this->renderCustomTools($this->prepends);

        foreach ($this->tools as $tool) {
            $renderMethod = 'render'.ucfirst($tool);
            $output .= $this->$renderMethod();
        }

        return $output.$this->renderCustomTools($this->appends);
    }
}
