<?php

namespace Encore\Admin\Models;

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
        // create a user.
        User::truncate();
        User::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => trans('admin.administrator'),
        ]);

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id'   => 0,
                'order'       => 1,
                'title'       => trans('admin.home'),
                'icon'        => 'fas fa-tachometer-alt',
                'uri'         => '/',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => trans('admin.auth_users'),
                'icon'      => 'fas fa-users',
                'uri'       => 'auth_users',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => trans('admin.auth_menus'),
                'icon'      => 'fas fa-bars',
                'uri'       => 'auth_menus',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
