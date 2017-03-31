<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;

class RolesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testRolesIndex()
    {
        $this->visit('admin/auth/roles')
            ->see('Roles')
            ->see('administrator');
    }

    public function testAddRole()
    {
        $this->visit('admin/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());
    }

    public function testAddRoleToUser()
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

        $userId2 = Administrator::offset(1)->take(2)->orderBy('id')->pluck('id')->first();

        $this->assertEquals(1, Role::count());

        $this->visit('admin/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->assertFalse(Administrator::find($userId2)->isRole('developer'));

        $roleId2 = Role::offset(1)->take(2)->orderBy('id')->pluck('id')->first();

        $this->visit('admin/auth/users/'.$userId2.'/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [$roleId2]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.role_users_table'), ['user_id' => $userId2, 'role_id' => $roleId2]);

        $this->assertTrue(Administrator::find($userId2)->isRole('developer'));

        $this->assertFalse(Administrator::find($userId2)->inRoles(['editor', 'operator']));
        $this->assertTrue(Administrator::find($userId2)->inRoles(['developer', 'operator', 'editor']));
    }

    public function testDeleteRole()
    {
        $this->assertEquals(1, Role::count());

        $this->visit('admin/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        list($roleId1, $roleId2) = Role::take(2)->orderBy('id')->pluck('id')->toArray();

        $this->delete('admin/auth/roles/' . $roleId2)
            ->assertEquals(1, Role::count());

        $this->delete('admin/auth/roles/' . $roleId1)
            ->assertEquals(0, Role::count());
    }

    public function testEditRole()
    {
        $this->visit('admin/auth/roles/1/edit')
            ->see('Roles')
            ->submitForm('Submit', ['name' => 'blablabla'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase(config('admin.database.roles_table'), ['name' => 'blablabla'])
            ->assertEquals(1, Role::count());
    }
}
