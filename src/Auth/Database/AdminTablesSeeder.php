<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Administrator::truncate();
        Administrator::create([
            'username'  => 'admin',
            'password'  => bcrypt('admin'),
            'name'      => 'Administrator',
        ]);

        Role::truncate();
        Role::create([
            'name'  => 'Administrator',
            'slug'  => 'administrator',
        ]);
    }
}
