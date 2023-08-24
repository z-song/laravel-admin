<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administratorModel = config('admin.database.users_model');
        $roleModel = config('admin.database.roles_model');
        $permissionModel = config('admin.database.permissions_model');
        $menuModel = config('admin.database.menu_model');

        // create a user.
        $administratorModel::truncate();
        $administratorModel::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name'     => 'Administrator',
        ]);

        // create a role.
        $roleModel::truncate();
        $roleModel::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        $administratorModel::first()->roles()->save($roleModel::first());

        //create a permission
        $permissionModel::truncate();
        $permissionModel::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
        ]);

        $roleModel::first()->permissions()->save($permissionModel::first());

        // add default menus.
        $menuModel::truncate();

        $menu[0] = $menuModel::create([
            'order'     => 1,
            'title'     => 'Dashboard',
            'icon'      => 'fa-bar-chart',
            'uri'       => '/',
        ]);
        $menu[0]->save();

        $menu[1] = $menuModel::create([
            'order'     => 2,
            'title'     => 'Admin',
            'icon'      => 'fa-tasks',
            'uri'       => '',
        ]);
        $menu[1]->parent()->associate($menu[0]);
        $menu[1]->save();

        $menu[2] = $menuModel::create([
            'order'     => 3,
            'title'     => 'Users',
            'icon'      => 'fa-users',
            'uri'       => 'auth/users',
        ]);
        $menu[2]->save();

        $menu[3] = $menuModel::create([
            'order'     => 4,
            'title'     => 'Roles',
            'icon'      => 'fa-user',
            'uri'       => 'auth/roles',
        ]);
        $menu[3]->parent()->associate($menu[2]);
        $menu[3]->save();

        $menu[4] = $menuModel::create([
            'order'     => 5,
            'title'     => 'Permission',
            'icon'      => 'fa-ban',
            'uri'       => 'auth/permissions',
        ]);
        $menu[4]->parent()->associate($menu[2]);
        $menu[4]->save();

        $menu[5] = $menuModel::create([
            'order'     => 6,
            'title'     => 'Menu',
            'icon'      => 'fa-bars',
            'uri'       => 'auth/menu',
        ]);
        $menu[5]->parent()->associate($menu[2]);
        $menu[5]->save();

        $menu[6] = $menuModel::create([
            'order'     => 7,
            'title'     => 'Operation log',
            'icon'      => 'fa-history',
            'uri'       => 'auth/logs',
        ]);
        $menu[6]->parent()->associate($menu[2]);
        $menu[6]->save();

        // add role to menu.
        $menu[2]->roles()->save($roleModel::first());
    }
}
