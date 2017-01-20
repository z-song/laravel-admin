<?php

use Encore\Admin\Auth\Database\Administrator;

class UsersTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Administrator::first();

        $this->be($this->user, 'admin');
    }

    public function testUsersIndexPage()
    {
        $this->visit('admin/auth/users')
            ->see('Administrator');
    }

    public function testCreateUser()
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

        $this->visit('admin/auth/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/auth/login')
            ->submitForm('Login', ['username' => $user['username'], 'password' => $user['password']])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');

        $this->assertFalse($this->app['auth']->guard('admin')->getUser()->isAdministrator());

        $this->dontSee('<span>Users</span>')
            ->dontSee('<span>Roles</span>')
            ->dontSee('<span>Permission</span>')
            ->dontSee('<span>Operation log</span>')
            ->dontSee('<span>Menu</span>');
    }

    public function testUpdateUser()
    {
        $this->visit('admin/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['name' => 'test', 'roles' => [1]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'password'              => $password,
            'password_confirmation' => $password,
            'roles'                 => [1],
        ];

        $this->visit('admin/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', $data)
            ->seePageIs('admin/auth/users')
            ->visit('admin/auth/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/auth/login')
            ->submitForm('Login', ['username' => $this->user->username, 'password' => $password])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');
    }
}
