<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Admin;
use Illuminate\Support\Facades\Lang;

class Action
{
    //const SHOW      = 'show';
    const EDIT      = 'edit';
    const DELETE    = 'delete';

    protected $defaultActions = [self::EDIT, self::DELETE];

    protected $customActions = [];

    protected $defaultActionViews = [
        //self::SHOW   => '<a href="/{path}/{id}"><i class="fa fa-eye"></i></a> ',
        self::EDIT   => '<a href="/{path}/{id}/edit"><i class="fa fa-edit"></i></a> ',
        self::DELETE => '<a href="javascript:void(0);" data-id="{id}" class="_delete"><i class="fa fa-trash"></i></a> ',
    ];

    protected $row;

    protected $path = '';

    public function __construct($actions = 'edit|delete')
    {
        $actions = explode('|', $actions);

        $this->defaultActions = array_intersect($actions, $this->defaultActions);
    }

    public function setRow(Row $row)
    {
        $this->row = $row;
    }

    public function add(\Closure $callback)
    {
        $this->customActions[] = $callback($this->row);

        //return $this;
    }

    public function initScript()
    {
        $this->path = app('router')->current()->getPath();

        $confirm = Lang::get('admin::lang.delete_confirm');
        $token = csrf_token();
        $script = <<<SCRIPT
            $('._delete').click(function() {
                var id = $(this).data('id');
                if(confirm("{$confirm}")) {
                    $.post('/{$this->path}/' + id, {_method:'delete','_token':'{$token}'}, function(data){
                        $.pjax.reload('#pjax-container');
                    });
                }
            });

            $('.grid-select-all').change(function() {
                if (this.checked) {
                    $('.grid-item').prop("checked", true);
                } else {
                    $('.grid-item').prop("checked", false);
                }
            });

            $('.batch-delete').on('click', function() {
                var selected = [];
                $('.grid-item:checked').each(function(){
                    selected.push($(this).data('id'));
                });

                if (selected.length == 0) {
                    return;
                }

                if(confirm("{$confirm}")) {
                    $.post('/{$this->path}/' + selected.join(), {_method:'delete','_token':'{$token}'}, function(data){
                        $.pjax.reload('#pjax-container');
                    });
                }
            });
SCRIPT;

        Admin::script($script);

    }

    public function render()
    {
        $this->initScript();

        $actionEntities = $this->customActions;

        foreach ($this->defaultActions as $action) {
            $actionEntities[] = str_replace(
                ['{path}', '{id}'],
                [$this->path, $this->row->id],
                $this->defaultActionViews[$action]
            );
        }

        return join(' ', $actionEntities);
    }

    public function __toString()
    {
        return $this->render();
    }
}
