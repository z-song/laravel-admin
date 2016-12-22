<?php

namespace Encore\Admin\Grid;

use Encore\Admin\Admin;

class Action
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * Default actions.
     *
     * @var array
     */
    protected $defaultActions = [self::EDIT, self::DELETE];

    /**
     * Custom actions.
     *
     * @var array
     */
    protected $customActions = [];

    /**
     * Views of default actions.
     *
     * @var array
     */
    protected $defaultActionViews = [
        self::EDIT   => '<a href="/{path}/{id}/edit"><i class="fa fa-edit"></i></a> ',
        self::DELETE => '<a href="javascript:void(0);" data-id="{id}" data-path="{path}" class="_delete"><i class="fa fa-trash"></i></a> ',
    ];

    /**
     * @var Row
     */
    protected $row;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * Generate a new Action instance.
     *
     * @param string $actions
     */
    public function __construct($actions = 'edit|delete')
    {
        $actions = explode('|', $actions);

        $this->defaultActions = array_intersect($actions, $this->defaultActions);
    }

    /**
     * Set row for action.
     *
     * @param Row $row
     */
    public function setRow(Row $row)
    {
        $this->row = $row;
    }

    /**
     * Set action path.
     *
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param callable $callback
     */
    public function add(\Closure $callback)
    {
        $this->customActions[] = $callback($this->row);
    }

    /**
     * Set up script for action.
     */
    protected function setUpScript()
    {
        $confirm = trans('admin::lang.delete_confirm');
        $token = csrf_token();
        $script = <<<SCRIPT

$('._delete').click(function() {
    var id = $(this).data('id');
    var path = $(this).data('path');
    if(confirm("{$confirm}")) {
        $.post('/'+ path +'/' + id, {_method:'delete','_token':'{$token}'}, function(data){

            if (typeof data === 'object') {
                if (data.status) {
                    noty({
                        text: "<strong>Succeeded!</strong><br/>"+data.message,
                        type:'success',
                        timeout: 3000
                    });
                } else {
                    noty({
                        text: "<strong>Failed!</strong><br/>"+data.message,
                        type:'error',
                        timeout: 3000
                    });
                }
            }

            $.pjax.reload('#pjax-container');
        });
    }
});

SCRIPT;

        Admin::script($script);
    }

    /**
     * Render actions.
     *
     * @return string
     */
    public function render()
    {
        $this->setUpScript();

        $actionEntities = $this->customActions;

        foreach ($this->defaultActions as $action) {
            $actionEntities[] = str_replace(
                ['{path}', '{id}'],
                [$this->path, $this->row->id()],
                $this->defaultActionViews[$action]
            );
        }

        return implode(' ', $actionEntities);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
