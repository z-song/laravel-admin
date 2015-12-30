<?php

namespace Encore\Admin\Grid;

class Action {

    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected $actions = [
        self::SHOW,
        self::EDIT,
        self::DELETE
    ];

    protected $actionViews = [
        self::SHOW      => '<a href="/{path}/{id}"><i class="fa fa-eye"></i></a> ',
        self::EDIT      => '<a href="/{path}/{id}/edit"><i class="fa fa-edit"></i></a> ',
        self::DELETE    => '<a href="/{path}/{id}/edit?_action=delete"><i class="fa fa-trash"></i></a> ',
    ];

    public function __construct($actions = 'show|edit|delete')
    {
        $actions = explode('|', $actions);

        $this->actions = array_intersect($actions, $this->actions);
    }

    public function render($id)
    {
        $html = '';
        $path = app('router')->current()->getPath();

        foreach($this->actions as $action) {
            $html .= str_replace(['{path}', '{id}'], [$path, $id], $this->actionViews[$action]);
        }

        return $html;
    }

}