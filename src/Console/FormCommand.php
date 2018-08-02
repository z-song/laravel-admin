<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class FormCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'laravel-admin form filed generator';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $modelName = $this->option('model');
        if (empty($modelName) || !class_exists($modelName)) {
            $this->error('Model does not exists !');
            return false;
        }

        // use doctrine/dbal
        $model = $this->laravel->make($modelName);
        $table = $model->getConnection()->getTablePrefix() . $model->getTable();
        $schema = $model->getConnection()->getDoctrineSchemaManager($table);

        if (!method_exists($schema, 'getDatabasePlatform')) {
            $this->error('You need to require doctrine/dbal: ~2.3 in your own composer.json to get database columns. ');
            $this->info('Using install command: composer require doctrine/dbal');
            return false;

        }

        // custom mapping the types that doctrine/dbal does not support
        $databasePlatform = $schema->getDatabasePlatform();
        $databasePlatform->registerDoctrineTypeMapping('enum', 'string');
        $databasePlatform->registerDoctrineTypeMapping('geometry', 'string');
        $databasePlatform->registerDoctrineTypeMapping('geometrycollection', 'string');
        $databasePlatform->registerDoctrineTypeMapping('linestring', 'string');
        $databasePlatform->registerDoctrineTypeMapping('multilinestring', 'string');
        $databasePlatform->registerDoctrineTypeMapping('multipoint', 'string');
        $databasePlatform->registerDoctrineTypeMapping('multipolygon', 'string');
        $databasePlatform->registerDoctrineTypeMapping('point', 'string');
        $databasePlatform->registerDoctrineTypeMapping('polygon', 'string');
        $databasePlatform->registerDoctrineTypeMapping('multipolygon', 'string');
        $databasePlatform->registerDoctrineTypeMapping('multipolygon', 'string');

        $database = null;
        if (strpos($table, '.')) {
            list($database, $table) = explode('.', $table);
        }
        $columns = $schema->listTableColumns($table, $database);

        $adminForm = '';
        if ($columns) {
            foreach ($columns as $column) {
                $name = $column->getName();
                if (in_array($name, ['id', 'created_at', 'deleted_at'])) {
                    continue;
                }
                $type = $column->getType()->getName();
                $comment = $column->getComment();
                $default = $column->getDefault();

                // set column fieldType
                switch ($type) {
                    case 'boolean':
                    case 'bool':
                        $fieldType = 'switch';
                        break;
                    case 'json':
                    case 'array':
                    case 'object':
                        $fieldType = 'text';
                        break;
                    case 'string':
                        switch ($name) {
                            case $this->checkColumn($name, ['email']):
                                $fieldType = 'email';
                                break;
                            case $this->checkColumn($name, ['password', 'pwd']):
                                $fieldType = 'password';
                                break;
                            case $this->checkColumn($name, ['url', 'link', 'src', 'href']):
                                $fieldType = 'url';
                                break;
                            case $this->checkColumn($name, ['ip']):
                                $fieldType = 'ip';
                                break;
                            case $this->checkColumn($name, ['mobile', 'phone']):
                                $fieldType = 'mobile';
                                break;
                            case $this->checkColumn($name, ['color', 'rgb']):
                                $fieldType = 'color';
                                break;
                            case $this->checkColumn($name, ['image', 'img', 'avatar']) :
                                $fieldType = 'image';
                                break;
                            case $this->checkColumn($name, ['file', 'attachment']) :
                                $fieldType = 'file';
                                break;
                            default:
                                $fieldType = 'text';
                        }
                        break;
                    case 'integer':
                    case 'bigint':
                    case 'smallint':
                    case 'timestamp':
                        $fieldType = 'number';
                        break;
                    case 'decimal':
                    case 'float':
                    case 'real':
                        $fieldType = 'decimal';
                        break;
                    case 'datetime':
                        $fieldType = 'datetime';
                        $default = "date('Y-m-d H:i:s')";
                        break;
                    case 'date':
                        $fieldType = 'date';
                        $default = "date('Y-m-d')";
                        break;
                    case 'text':
                    case 'blob':
                        $fieldType = 'textarea';
                        $default = '';
                        break;
                    default:
                        $fieldType = 'text';
                }

                // set column comment
                $comment = $comment ? $comment : $name;

                // set column defaultValue
                switch ($default) {
                    case null:
                        $defaultValue = "''";
                        break;
                    case "date('Y-m-d H:i:s')":
                    case "date('Y-m-d')":
                    case is_numeric($default):
                        $defaultValue = $default;
                        break;
                    default:
                        $defaultValue = "'{$default}'";
                }

                $adminForm .= "\$form->{$fieldType}('{$name}', '{$comment}')->default({$defaultValue});\n";
            }
            $this->alert("laravel-admin form filed generator for {$modelName}:");
            $this->info($adminForm);
        }

    }

    /**
     * Check if the table column contains the specified keywords of the array.
     *
     * @param string $haystack
     * @param array $needle
     * @return bool
     */
    private function checkColumn(string $haystack, array $needle)
    {
        foreach ($needle as $value) {
            if (strstr($haystack, $value) !== false) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', null, InputOption::VALUE_REQUIRED,
                'The eloquent model that should be use as controller data source.',],
        ];
    }
}
