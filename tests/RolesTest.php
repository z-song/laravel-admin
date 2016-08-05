<?php

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;

class RolesTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Administrator::first();
    }

    public function testRolesIndex()
    {
        $this->be($this->user);

        $this->visit('admin/auth/roles')
            ->see('Roles')
            ->see('administrator');
    }

    public function testAddRole()
    {
        $this->be($this->user);

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
        $this->be($this->user);

        $this->assertEquals(1, Role::count());

        $this->delete('admin/auth/roles/1')
            ->assertEquals(0, Role::count());
    }

    public function testEditRole()
    {
        $this->be($this->user);

        $this->visit('admin/auth/roles/1/edit')
            ->see('Roles')
            ->submitForm('Submit', ['name' => 'blablabla'])
            ->seePageIs('admin/auth/roles')
            ->seeInDatabase('roles', ['name' => 'blablabla'])
            ->assertEquals(1, Role::count());
    }
}
