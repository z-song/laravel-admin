<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.permissions'));
            $content->description(trans('admin::lang.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.permissions'));
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.permissions'));
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Permission::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('admin::lang.slug'));
            $grid->name(trans('admin::lang.name'));

            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Permission::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', trans('admin::lang.slug'))->rules('required');
            $form->text('name', trans('admin::lang.name'))->rules('required');

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}
