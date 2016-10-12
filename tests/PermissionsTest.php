<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;

class PermissionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testPermissionsIndex()
    {
        $this->visit('admin/auth/permissions')
            ->see('Permissions');
    }

    public function testAddAndDeletePermission()
    {
        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit'])
            ->seePageIs('admin/auth/permissions')
            ->seeInDatabase(config('admin.database.permissions_table'), ['slug' => 'can-edit'])
            ->seeInDatabase(config('admin.database.permissions_table'), ['name' => 'Can edit'])
            ->assertEquals(1, Permission::count());
    }

    public function testAddPermissionToRole()
    {
        $this->visit('admin/auth/roles/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1]])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.role_permissions_table'), ['role_id' => 1, 'permission_id' => 1]);
    }

    public function testDeletePermission()
    {
        $this->delete('admin/auth/permissions/1')
            ->assertEquals(0, Permission::count());
    }

    public function testEditPermission()
    {
        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit'])
            ->seePageIs('admin/auth/permissions')
            ->seeInDatabase(config('admin.database.permissions_table'), ['slug' => 'can-edit'])
            ->seeInDatabase(config('admin.database.permissions_table'), ['name' => 'Can edit'])
            ->assertEquals(1, Permission::count());

        $this->visit('admin/auth/permissions/1/edit')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete'])
            ->seePageIs('admin/auth/permissions')
            ->seeInDatabase(config('admin.database.permissions_table'), ['slug' => 'can-delete'])
            ->assertEquals(1, Permission::count());
    }
}
