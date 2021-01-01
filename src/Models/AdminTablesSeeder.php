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
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => 'Administrator',
        ]);

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Home',
                'icon'      => 'fas fa-tachometer-alt',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Users',
                'icon'      => 'fas fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => 'Menu',
                'icon'      => 'fas fa-bars',
                'uri'       => 'auth/menu',
            ],
        ]);
    }
}
