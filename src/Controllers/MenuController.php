<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

class MenuController extends Controller
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
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.list'));

            $content->row(function (Row $row) {

                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menu'));

                    $options = Menu::selectOptions()->prepend('Root', 0);
                    $form->select('parent_id', trans('admin::lang.parent_id'))->options($options);
                    $form->text('title', trans('admin::lang.title'))->rules('required');
                    $form->icon('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin::lang.uri'));
                    $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

                    $column->append((new Box(trans('admin::lang.new'), $form))->style('success'));
                });
            });
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return Menu::tree(function ($branch) {

            $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

            if (isset($branch['children'])) {

                $uri = admin_url($branch['uri']);

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * Store input data.
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        if (Request::has('_order')) {
            return response()->json([
                'status' => Menu::tree()->saveOrder(Request::input('_order')),
            ]);
        }

        return $this->form()->store();
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Menu::form(function (Form $form) {
            $form->display('id', 'ID');

            $options = Menu::selectOptions()->prepend('Root', 0);

            $form->select('parent_id', trans('admin::lang.parent_id'))->options($options);
            $form->text('title', trans('admin::lang.title'))->rules('required');
            $form->icon('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
            $form->text('uri', trans('admin::lang.uri'));
            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
