<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class LogController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.operation_log'));
            $content->description(trans('admin::lang.list'));

            $grid = Admin::grid(OperationLog::class, function (Grid $grid) {
                $grid->model()->orderBy('id', 'DESC');

                $grid->id('ID')->sortable();
                $grid->user()->name();
                $grid->method()->value(function ($method) {
                    $color = array_get(OperationLog::$methodColors, $method, 'grey');

                    return "<span class=\"badge bg-$color\">$method</span>";
                });
                $grid->path()->label('info');
                $grid->ip()->label('primary');
                $grid->input()->value(function ($input) {
                    $input = json_decode($input, true);
                    $input = array_except($input, '_pjax');

                    return '<code>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</code>';
                });

                $grid->created_at(trans('admin::lang.created_at'));

                $grid->rows(function ($row) {
                    $row->actions('delete');
                });

                $grid->disableCreation();

                $grid->filter(function ($filter) {
                    $filter->is('user_id', 'User')->select(Administrator::all()->pluck('name', 'id'));
                    $filter->is('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
                    $filter->like('path');
                    $filter->is('ip');
                });
            });

            $content->body($grid);
        });
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);

        OperationLog::destroy(array_filter($ids));

        return response()->json(['msg' => 'delete success!']);
    }
}
