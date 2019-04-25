<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;

class LogController extends Controller
{
    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('admin.operation_log'))
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OperationLog());

        $grid->model()->orderBy('id', 'DESC');

        $grid->id('ID')->sortable();
        $grid->user()->name('User');
        $grid->method()->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');

            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $grid->path()->label('info');
        $grid->ip()->label('primary');
        $grid->input()->display(function ($input) {
            $input = json_decode($input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<code>{}</code>';
            }

            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
        });

        $grid->created_at(trans('admin.created_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->disableCreation();

        $grid->filter(function ($filter) {
            $userModel = config('admin.database.users_model');

            $filter->equal('user_id', 'User')->select($userModel::all()->pluck('name', 'id'));
            $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
            $filter->like('path');
            $filter->equal('ip');
        });

        return $grid;
    }

    /**
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);

        if (OperationLog::destroy(array_filter($ids))) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }
}
