<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Auth;

class RolesTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        Auth::login(Administrator::first());
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
            ->seeInDatabase('roles', ['slug' => 'developer'])
            ->seeInDatabase('roles', ['name' => 'Developer...'])
            ->assertEquals(2, Role::count());
    }

    public function testDeleteRole()
    {
        //$this->assertEquals(1, Role::count());

        $this->delete('admin/auth/roles/1')
            ->assertEquals(1, Role::count());
    }

    public function testEditRole()
    {
        $this->visit('admin/auth/roles/1/edit')
            ->see('Roles')
            ->submitForm('Submit', ['name' => 'blablabla'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase('roles', ['name' => 'blablabla'])
            ->assertEquals(1, Role::count());
    }
}
