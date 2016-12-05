<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Menu as MenuModel;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Menu\Menu;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Callout;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

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

            $content->row(function (Row $row) {
                $row->column(5, function (Column $column) {
                    $column->append($this->callout());

                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menu'));

                    $options = [0 => 'Root'] + MenuModel::buildSelectOptions();
                    $form->select('parent_id', trans('admin::lang.parent_id'))->options($options);
                    $form->text('title', trans('admin::lang.title'))->rules('required');
                    $form->text('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required');
                    $form->text('uri', trans('admin::lang.uri'));
                    $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

                    $column->append((new Box(trans('admin::lang.new'), $form))->style('success'));
                });

                $menu = new Menu(new MenuModel());

                $row->column(7, $menu);
            });

            Admin::script($this->script());
        });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->action(
            '\Encore\Admin\Controllers\MenuController@edit', ['id' => $id]
        );
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
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        if (Request::input('parent_id') == $id) {
            throw new \Exception(trans('admin::lang.parent_select_error'));
        }

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
            return response()->json([
                'status'  => true,
                'message' => trans('admin::lang.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin::lang.delete_failed'),
            ]);
        }
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        if (Request::has('_order')) {
            $menu = new Menu(new MenuModel());

            return response()->json([
                'status' => $menu->saveTree(Request::input('_order')),
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
        return Admin::form(MenuModel::class, function (Form $form) {
            $form->display('id', 'ID');

            $options = [0 => 'Root'] + MenuModel::buildSelectOptions();

            $form->select('parent_id', trans('admin::lang.parent_id'))->options($options);
            $form->text('title', trans('admin::lang.title'))->rules('required');
            $form->text('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required');
            $form->text('uri', trans('admin::lang.uri'));
            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }

    protected function script()
    {
        return <<<'EOT'

$('.menu-tools').on('click', function(e){
    var target = $(e.target),
        action = target.data('action');
    if (action === 'expand-all') {
        $('.dd').nestable('expandAll');
    }
    if (action === 'collapse-all') {
        $('.dd').nestable('collapseAll');
    }
});
EOT;
    }

    /**
     * @return Callout
     */
    protected function callout()
    {
        $text = 'For icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';

        return new Callout($text, 'Tips', 'info');
    }
}
