<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Table;
use Encore\Admin\Show;

class RoleController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.roles');
    }

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $roleModel = config('admin.database.roles_model');

        $table = new Table(new $roleModel());

        $table->column('id', 'ID')->sortable();
        $table->column('slug', trans('admin.slug'));
        $table->column('name', trans('admin.name'));

        $table->column('permissions', trans('admin.permission'))->pluck('name')->label();

        $table->column('created_at', trans('admin.created_at'));
        $table->column('updated_at', trans('admin.updated_at'));

        $table->actions(function (Table\Displayers\Actions $actions) {
            if ($actions->row->slug == 'administrator') {
                $actions->disableDelete();
            }
        });

        $table->tools(function (Table\Tools $tools) {
            $tools->batch(function (Table\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $table;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $roleModel = config('admin.database.roles_model');

        $show = new Show($roleModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('slug', trans('admin.slug'));
        $show->field('name', trans('admin.name'));
        $show->field('permissions', trans('admin.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $roleModel());

        $form->display('id', 'ID');

        $form->text('slug', trans('admin.slug'))->rules('required');
        $form->text('name', trans('admin.name'))->rules('required');
        $form->listbox('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
