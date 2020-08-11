<?php

namespace Encore\Admin\Http\Controllers;

use Encore\Admin\Models\OperationLog;
use Encore\Admin\Table;
use Illuminate\Support\Arr;

class LogController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('admin.operation_log');
    }

    /**
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new OperationLog());

        $table->model()->orderBy('id', 'DESC');

        $table->column('id', 'ID')->sortable();
        $table->column('user.name', 'User');
        $table->column('method')->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');

            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $table->column('path')->label('info');
        $table->column('ip')->label('primary');
        $table->column('input')->display(function ($input) {
            $input = json_decode($input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<code>{}</code>';
            }

            return '<pre><code>'.json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG).'</code></pre>';
        });

        $table->column('created_at', trans('admin.created_at'));

        $table->actions(function (Table\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $table->disableCreateButton();

        $table->filter(function (Table\Filter $filter) {
            $userModel = config('admin.database.users_model');

            $filter->equal('user_id', 'User')->select($userModel::all()->pluck('name', 'id'));
            $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
            $filter->like('path');
            $filter->equal('ip');
        });

        return $table;
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
