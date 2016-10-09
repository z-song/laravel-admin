<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Callout;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.list'));

            $content->body($this->tree());
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
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.edit'));

            $content->row($this->callout());
            $content->row($this->form()->edit($id));
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
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.create'));

            $content->row($this->callout());
            $content->row($this->form());
        });
    }

    /**
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            return response()->json(['msg' => 'delete success!']);
        }
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Make a tree builder.
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

            $options = [0 => 'Root'] + Menu::buildSelectOptions();

            $form->select('parent_id', trans('admin::lang.parent_id'))->options($options);
            $form->text('title', trans('admin::lang.title'))->rules('required');
            $form->text('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required');
            $form->text('uri', trans('admin::lang.uri'));
            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }

    /**
     * @return Callout
     */
    protected function callout()
    {
        $text = 'For icons see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';

        return new Callout($text, 'Tips', 'info');
    }
}
