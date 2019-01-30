<?php

namespace Encore\Admin\Console;

use Encore\Admin\Admin;
use Illuminate\Console\Command;

class ExportSeedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:export-seed {classname=AdminTablesSeeder}
                                              {--users : add to seed users tables}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export seed a Laravel-admin database tables menu, roles and permissions';
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('classname');
        $exceptFields = [];
        $exportUsers = $this->option('users');
        
        $seedFile = $this->laravel->databasePath() . '/seeds/' . $name . '.php';
        $contents = $this->getStub('AdminTablesSeeder');
        
        $replaces = [
            'DummyClass' => $name,
            
            'ClassMenu'       => config('admin.database.menu_model'),
            'ClassPermission' => config('admin.database.permissions_model'),
            'ClassRole'       => config('admin.database.roles_model'),
            
            'TableRoleMenu'        => config('admin.database.role_menu_table'),
            'TableRolePermissions' => config('admin.database.role_permissions_table'),
            
            'ArrayMenu'       => $this->getTableDataArrayAsString(config('admin.database.menu_table'), $exceptFields),
            'ArrayPermission' => $this->getTableDataArrayAsString(config('admin.database.permissions_table'), $exceptFields),
            'ArrayRole'       => $this->getTableDataArrayAsString(config('admin.database.roles_table'), $exceptFields),
            
            'ArrayPivotRoleMenu'        => $this->getTableDataArrayAsString(config('admin.database.role_menu_table'), $exceptFields),
            'ArrayPivotRolePermissions' => $this->getTableDataArrayAsString(config('admin.database.role_permissions_table'), $exceptFields),
        ];
        
        if ($exportUsers) {
            $replaces  = array_merge($replaces, [
                'ClassUsers'            => config('admin.database.users_model'),
                'TableRoleUsers'        => config('admin.database.role_users_table'),
                'TablePermissionsUsers' => config('admin.database.user_permissions_table'),
    
                'ArrayUsers'                 => $this->getTableDataArrayAsString(config('admin.database.users_table'), $exceptFields),
                'ArrayPivotRoleUsers'        => $this->getTableDataArrayAsString(config('admin.database.role_users_table'), $exceptFields),
                'ArrayPivotPermissionsUsers' => $this->getTableDataArrayAsString(config('admin.database.user_permissions_table'), $exceptFields),
            ]);
            
        } else {
            $contents = preg_replace('/\/\/ users tables[\s\S]*?(?=\/\/ finish)/mu', '', $contents);
        }
    
        $contents = str_replace(array_keys($replaces), array_values($replaces), $contents);
        
        $this->laravel['files']->put($seedFile, $contents);
        
        $this->line('<info>Admin tables seed file was created:</info> ' . str_replace(base_path(), '', $seedFile));
        $this->line("Use: <info>php artisan db:seed --class={$name}</info>");
    }
    
    /**
     * Get data array from table as string result var_export
     *
     * @param string $table
     * @param array $exceptFields
     * @return string
     */
    protected function getTableDataArrayAsString($table, $exceptFields = [])
    {
        $fields = \DB::getSchemaBuilder()->getColumnListing($table);
        $fields = array_diff($fields, $exceptFields);
        
        $array = \DB::table($table)->get($fields)->map(function ($item) {
            return (array)$item;
        })->all();
        
        $string = var_export($array, true);
        
        // some pretty view
        return str_replace(["\n  ", "\n)"], ["\n" . str_repeat(' ', 16), "\n" . str_repeat(' ', 12) . ')'], $string);
    }
    
    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__ . "/stubs/$name.stub");
    }
}
