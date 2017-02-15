<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Scaffold\ControllerCreator;
use Encore\Admin\Scaffold\MigrationCreator;
use Encore\Admin\Scaffold\ModelCreator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;

class ScaffoldController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Scaffold');

            $dbTypes = [
                'string',
                'integer',
                'text',
                'float',
                'double',
                'decimal',
                'boolean',
                'date',
                'time',
                'dateTime',
                'timestamp',
                'char',
                'mediumText',
                'longText',
                'tinyInteger',
                'smallInteger',
                'mediumInteger',
                'bigInteger',
                'unsignedTinyInteger',
                'unsignedSmallInteger',
                'unsignedMediumInteger',
                'unsignedInteger',
                'unsignedBigInteger',
                'enum',
                'json',
                'jsonb',
                'dateTimeTz',
                'timeTz',
                'timestampTz',
                'nullableTimestamps',
                'binary',
                'ipAddress',
                'macAddress',
            ];

            $content->row(view('admin::helpers.scaffold', compact('dbTypes')));
        });
    }

    public function store(Request $request)
    {
        $paths = [];

        try {

            // Create controller
            $paths['controller'] = (new ControllerCreator($request->get('controller_name')))
                ->create($request->get('model_name'));

            // Create model.
            $modelCreator = new ModelCreator($request->get('table_name'), $request->get('model_name'));

            $paths['model'] = $modelCreator->create(
                $request->get('primary_key'),
                $request->get('timestamps') == 'on',
                $request->get('soft_deletes') == 'on'
            );

            // Create migration
            $migrationName = 'create_'.$request->get('table_name').'_table';

            $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint(
                $request->get('fields'),
                $request->get('primary_key', 'id'),
                $request->get('use_timestamps') == 'on',
                $request->get('use_soft_deletes') == 'on'
            )->create($migrationName, database_path('migrations'), $request->get('table_name'));

        } catch (\Exception $exception) {
            return $this->backWithException($exception);
        }

        return $paths;
    }

    protected function backWithException(\Exception $exception)
    {
        $error = new MessageBag([
            'title'   => 'Error',
            'message' => $exception->getMessage(),
        ]);

        return back()->withInput()->with(compact('error'));
    }
}
