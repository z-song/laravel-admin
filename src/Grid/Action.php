<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Lang;

class Action
{

    const SHOW      = 'show';
    const EDIT      = 'edit';
    const DELETE    = 'delete';

    protected $actions = [
        self::SHOW,
        self::EDIT,
        self::DELETE
    ];

    protected $actionViews = [
        self::SHOW   => '<a href="/{path}/{id}"><i class="fa fa-eye"></i></a> ',
        self::EDIT   => '<a href="/{path}/{id}/edit"><i class="fa fa-edit"></i></a> ',
        self::DELETE => '<a href="javascript:void(0);" data-id="{id}" class="_delete"><i class="fa fa-trash"></i></a> ',
    ];

    protected $grid = null;

    protected $path = '';

    public function __construct($actions = 'show|edit|delete')
    {
        $actions = explode('|', $actions);

        $this->actions = array_intersect($actions, $this->actions);

        //$this->initScript();
    }

    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;
    }

    public function initScript()
    {
        $this->path = $this->grid->resource(); //app('router')->current()->getPath();

        $confirm = Lang::get('admin::lang.delete_confirm');
        $token = csrf_token();
        $script = <<<SCRIPT
            $('._delete').click(function() {
                var id = $(this).data('id');
                if(confirm("{$confirm}")) {
                    $.post('/{$this->path}/' + id, {_method:'delete','_token':'{$token}'}, function(data){
                        location.reload(true);
                    });
                }
            });
SCRIPT;

        Admin::script($script);

    }

    public function render($id)
    {
        $this->initScript();

        $html = '';

        foreach ($this->actions as $action) {
            $html .= str_replace(['{path}', '{id}'], [$this->path, $id], $this->actionViews[$action]);
        }

        return $html;
    }
}
