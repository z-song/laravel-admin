<?php

use Encore\Admin\Auth\Database\Administrator;

class UsersTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Administrator::first();
    }

    public function testUsersIndexPage()
    {
        $this->be($this->user);

        $this->visit('admin/auth/users')
            ->see('Administrator');
    }

    public function testCreateUser()
    {
        $user = [
            'username' => 'Test',
            'name'     => 'Name',
            'password' => '123456',
        ];

        $this->be($this->user);

        $this->visit('admin/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('admin/auth/users')
            ->seeInDatabase('administrators', ['username' => 'Test']);

        $this->visit('admin/auth/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/auth/login')
            ->submitForm('Login', ['username' => $user['username'], 'password' => $user['password']])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');
    }

    public function testUpdateUser()
    {
        $this->be($this->user);

        $this->visit('admin/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['name' => 'test'])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase('administrators', ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $this->be($this->user);

        $this->visit('admin/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['password' => $password])
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
