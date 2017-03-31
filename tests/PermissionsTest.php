<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;

class PermissionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testPermissionsIndex()
    {
        $this->assertTrue(Administrator::first()->isAdministrator());

        $this->visit('admin/auth/permissions')
            ->see('Permissions');
    }

    public function testAddAndDeletePermissions()
    {
        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit'])
            ->seePageIs('admin/auth/permissions')
            ->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete', 'name' => 'Can delete'])
            ->seePageIs('admin/auth/permissions')
            ->seeInDatabase(config('admin.database.permissions_table'), ['slug' => 'can-edit', 'name' => 'Can edit'])
            ->seeInDatabase(config('admin.database.permissions_table'), ['slug' => 'can-delete', 'name' => 'Can delete'])
            ->assertEquals(2, Permission::count());

        $this->assertTrue(Administrator::first()->can('can-edit'));
        $this->assertTrue(Administrator::first()->can('can-delete'));

        $this->delete('admin/auth/permissions/1')
            ->assertEquals(1, Permission::count());

        $this->delete('admin/auth/permissions/2')
            ->assertEquals(0, Permission::count());
    }

    public function testAddPermissionToRole()
    {
        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create'])
            ->seePageIs('admin/auth/permissions');

        $this->assertEquals(1, Permission::count());

        $this->visit('admin/auth/roles/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1]])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.role_permissions_table'), ['role_id' => 1, 'permission_id' => 1]);
    }

    public function testAddPermissionToUser()
    {
        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create'])
            ->seePageIs('admin/auth/permissions');

        $this->assertEquals(1, Permission::count());

        $this->visit('admin/auth/users/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1], 'roles' => [1]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.user_permissions_table'), ['user_id' => 1, 'permission_id' => 1])
            ->seeInDatabase(config('admin.database.role_users_table'), ['user_id' => 1, 'role_id' => 1]);
    }

    public function testAddUserAndAssignPermission()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('admin/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.users_table'), ['username' => 'Test']);

        $this->assertEquals(2, Administrator::count());
        $userId2 = Administrator::offset(1)->take(2)->orderBy('id')->pluck('id')->first();

        $this->assertFalse(Administrator::find($userId2)->isAdministrator());

        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-update', 'name' => 'Can Update'])
            ->seePageIs('admin/auth/permissions');

        $this->assertEquals(1, Permission::count());

        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove'])
            ->seePageIs('admin/auth/permissions');

        $this->assertEquals(2, Permission::count());

        $permissionUpdateId = Permission::where('slug', 'can-update')->first()->id;
        $permissionRemoveId = Permission::where('slug', 'can-remove')->first()->id;

        $permissionsTable = config('admin.database.user_permissions_table');

        $this->visit('admin/auth/users/'.$userId2.'/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [$permissionUpdateId]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase($permissionsTable, ['user_id' => $userId2, 'permission_id' => $permissionUpdateId]);

        $this->assertTrue(Administrator::find($userId2)->can('can-update'));
        $this->assertTrue(Administrator::find($userId2)->cannot('can-remove'));

        $this->visit('admin/auth/users/'.$userId2.'/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [$permissionRemoveId]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase($permissionsTable, ['user_id' => $userId2, 'permission_id' => $permissionRemoveId]);

        $this->assertTrue(Administrator::find($userId2)->can('can-remove'));

        $this->visit('admin/auth/users/'.$userId2.'/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => []])
            ->seePageIs('admin/auth/users')
            ->missingFromDatabase($permissionsTable, ['user_id' => $userId2, 'permission_id' => $permissionUpdateId])
            ->missingFromDatabase($permissionsTable, ['user_id' => $userId2, 'permission_id' => $permissionRemoveId]);

        $this->assertTrue(Administrator::find($userId2)->cannot('can-update'));
        $this->assertTrue(Administrator::find($userId2)->cannot('can-remove'));
    }

    public function testPermissionThroughRole()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // 1.add a user
        $this->visit('admin/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.users_table'), ['username' => 'Test']);

        $userId2 = Administrator::offset(1)->take(2)->orderBy('id')->pluck('id')->first();

        $this->assertFalse(Administrator::find($userId2)->isAdministrator());

        // 2.add a role
        $this->visit('admin/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $roleId2 = Role::offset(1)->take(2)->orderBy('id')->pluck('id')->first();

        $this->assertFalse(Administrator::find($userId2)->isRole('developer'));

        // 3.assign role to user
        $this->visit('admin/auth/users/'.$userId2.'/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [2]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.role_users_table'), ['user_id' => $userId2, 'role_id' => $roleId2]);

        $this->assertTrue(Administrator::find($userId2)->isRole('developer'));

        //  4.add a permission
        $this->visit('admin/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove'])
            ->seePageIs('admin/auth/permissions');

        $this->assertEquals(1, Permission::count());

        $permissionRemoveId = Permission::where('slug', 'can-remove')->first()->id;

        $this->assertTrue(Administrator::find($userId2)->cannot('can-remove'));

        // 5.assign permission to role
        $this->visit('admin/auth/roles/'.$roleId2.'/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [$permissionRemoveId]])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.role_permissions_table'), ['role_id' => $roleId2, 'permission_id' => $permissionRemoveId]);

        $this->assertTrue(Administrator::find($userId2)->can('can-remove'));
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

        $id = Permission::first()->id;

        $this->visit('admin/auth/permissions/'.$id.'/edit')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete'])
            ->seePageIs('admin/auth/permissions')
            ->seeInDatabase(config('admin.database.permissions_table'), ['slug' => 'can-delete'])
            ->assertEquals(1, Permission::count());
    }
}
