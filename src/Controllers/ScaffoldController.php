<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Scaffold\ControllerCreator;
use Encore\Admin\Scaffold\MigrationCreator;
use Encore\Admin\Scaffold\ModelCreator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\MessageBag;

class ScaffoldController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Scaffold');

            $dbTypes = [
                'string', 'integer', 'text', 'float', 'double', 'decimal', 'boolean', 'date', 'time',
                'dateTime', 'timestamp', 'char', 'mediumText', 'longText', 'tinyInteger', 'smallInteger',
                'mediumInteger', 'bigInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger',
                'unsignedInteger', 'unsignedBigInteger', 'enum', 'json', 'jsonb', 'dateTimeTz', 'timeTz',
                'timestampTz', 'nullableTimestamps', 'binary', 'ipAddress', 'macAddress',
            ];

            $content->row(view('admin::helpers.scaffold', compact('dbTypes')));
        });
    }

    public function store(Request $request)
    {
        $paths = [];
        $message = '';

        try {

            // 1. Create model.
            if (in_array('model', $request->get('create'))) {
                $modelCreator = new ModelCreator($request->get('table_name'), $request->get('model_name'));

                $paths['model'] = $modelCreator->create(
                    $request->get('primary_key'),
                    $request->get('timestamps') == 'on',
                    $request->get('soft_deletes') == 'on'
                );
            }

            // 2. Create controller.
            if (in_array('controller', $request->get('create'))) {
                $paths['controller'] = (new ControllerCreator($request->get('controller_name')))
                    ->create($request->get('model_name'));
            }

            // 3. Create migration.
            if (in_array('migration', $request->get('create'))) {
                $migrationName = 'create_' . $request->get('table_name') . '_table';

                $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint(
                    $request->get('fields'),
                    $request->get('primary_key', 'id'),
                    $request->get('timestamps') == 'on',
                    $request->get('soft_deletes') == 'on'
                )->create($migrationName, database_path('migrations'), $request->get('table_name'));
            }

            // 4. Run migrate.
            if (in_array('migrate', $request->get('create'))) {
                Artisan::call('migrate');
                $message = Artisan::output();
            }

        } catch (\Exception $exception) {

            // Delete generated files if exception thrown.
            app('files')->delete($paths);

            return $this->backWithException($exception);
        }

        return $this->backWithSuccess($paths, $message);
    }

    protected function backWithException(\Exception $exception)
    {
        $error = new MessageBag([
            'title'   => 'Error',
            'message' => $exception->getMessage(),
        ]);

        return back()->withInput()->with(compact('error'));
    }

    protected function backWithSuccess($paths, $message)
    {
        $messages = [];

        foreach ($paths as $name => $path) {
            $messages[] = ucfirst($name) . ": $path";
        }

        $messages[] = "<br />$message";

        $success = new MessageBag([
            'title'   => 'Success',
            'message' => join('<br />', $messages),
        ]);

        return back()->with(compact('success'));
    }
}
