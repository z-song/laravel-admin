<?php

use Encore\Admin\Models\User;

class UsersTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();

        $this->be($this->user, 'admin');
    }

    public function testUsersIndexPage()
    {
        $this->visit('admin/auth_users')
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

        // create user
        $this->visit('admin/auth_users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('admin/auth_users')
            ->seeInDatabase(config('admin.database.users_table'), ['username' => 'Test']);

        // assign role to user
        $this->visit('admin/auth_users/2/edit')
            ->see('Edit')
            ->seePageIs('admin/auth_users');

        $this->visit('admin/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/login')
            ->submitForm('Login', ['username' => $user['username'], 'password' => $user['password']])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');

        $this->assertTrue($this->app['auth']->guard('admin')->getUser()->isAdministrator());

        $this->see('<span>Users</span>')
            ->see('<span>Menu</span>');
    }

    public function testUpdateUser()
    {
        $this->visit('admin/auth_users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['name' => 'test'])
            ->seePageIs('admin/auth_users')
            ->seeInDatabase(config('admin.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'password'              => $password,
            'password_confirmation' => $password,
        ];

        $this->visit('admin/auth_users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', $data)
            ->seePageIs('admin/auth_users')
            ->visit('admin/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/login')
            ->submitForm('Login', ['username' => $this->user->username, 'password' => $password])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');
    }
}
