<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            return response()->json(['msg' => 'delete success!']);
        }
    }

    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.list'));
            $content->body($this->tree()->render());
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
            $content->header(trans('admin::lang.administrator'));
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
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a tree builder
     *
     * @return Tree
     */
    public function tree()
    {
        return Admin::tree(Menu::class);
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Menu::class, function (Form $form) {
            $form->display('id', 'ID');

            $parent = [0 => 'Root'];
            $menu = Menu::all()->pluck('title', 'id')->toArray();

            $form->select('parent_id')->options(array_merge($parent, $menu));
            $form->number('order');
            $form->text('title')->rules('required');
            $form->text('icon')->rules('required');
            $form->text('uri');

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}
