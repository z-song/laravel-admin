<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
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
     * @return void
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

        $admin_form = '';
        if ($columns) {
            foreach ($columns as $column) {
                $name = $column->getName();
                if (in_array($name, ['id', 'created_at', 'deleted_at'])) {
                    continue;
                }
                $type = $column->getType()->getName();
                $comment = $column->getComment();
                $default = $column->getDefault();
                if ($default === '') {
                    $default = "''";
                }

                switch ($type) {
                    case 'boolean':
                    case 'bool':
                        $field_type = 'switch';
                        break;
                    case 'json':
                    case 'array':
                    case 'object':
                        $field_type = 'text';
                        break;
                    case 'string':
                        switch ($name) {
                            case $this->check_column($name, ['email']):
                                $field_type = 'email';
                                break;
                            case $this->check_column($name, ['password', 'pwd']):
                                $field_type = 'password';
                                break;
                            case $this->check_column($name, ['url', 'link', 'src', 'href']):
                                $field_type = 'url';
                                break;
                            case $this->check_column($name, ['ip']):
                                $field_type = 'ip';
                                break;
                            case $this->check_column($name, ['mobile', 'phone']):
                                $field_type = 'mobile';
                                break;
                            case $this->check_column($name, ['color', 'rgb']):
                                $field_type = 'color';
                                break;
                            case $this->check_column($name, ['image', 'img', 'avatar']) :
                                $field_type = 'image';
                                break;
                            case $this->check_column($name, ['file', 'attachment']) :
                                $field_type = 'file';
                                break;
                            default:
                                $field_type = 'text';
                        }
                        break;
                    case 'integer':
                    case 'bigint':
                    case 'smallint':
                    case 'timestamp':
                        $field_type = 'number';
                        break;
                    case 'decimal':
                    case 'float':
                    case 'real':
                        $field_type = 'decimal';
                        break;
                    case 'datetime':
                        $field_type = 'datetime';
                        $default = "date('Y-m-d H:i:s')";
                        break;
                    case 'date':
                        $field_type = 'date';
                        $default = "date('Y-m-d')";
                        break;
                    case 'text':
                    case 'blob':
                        $field_type = 'textarea';
                        $default = "''";
                        break;
                    default:
                        $field_type = 'text';
                }
                $admin_form .= "\$form->{$field_type}('{$name}', '{$comment}')->default({$default});\n";
            }
            $this->alert("laravel-admin form filed generator for {$modelName}:");
            $this->info($admin_form);
        }

    }

    /**
     * Check if the table column contains the specified keywords of the array
     * @param string $haystack
     * @param array $needle
     * @return bool
     */
    private function check_column(string $haystack, array $needle)
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
