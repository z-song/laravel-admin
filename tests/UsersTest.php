<?php

use Encore\Admin\Auth\Database\Administrator;

class UsersTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = Administrator::first();

        $this->be($this->user, 'admin');
    }

    public function testUsersIndexPage()
    {
        $response = $this->get('admin/auth/users');
        
        $response->assertStatus(200)
            ->assertSee('Administrator');
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
        $response = $this->get('admin/auth/users/create');
        $response->assertStatus(200)->assertSee('Create');
        
        $response = $this->post('admin/auth/users', $user);
        $response->assertRedirect('admin/auth/users');
        $this->assertDatabaseHas(config('admin.database.users_table'), ['username' => 'Test']);

        // assign role to user
        $response = $this->get('admin/auth/users/2/edit');
        $response->assertStatus(200)->assertSee('Edit');
        
        $response = $this->put('admin/auth/users/2', ['roles' => [1]]);
        $response->assertRedirect('admin/auth/users');
        $this->assertDatabaseHas(config('admin.database.role_users_table'), ['user_id' => 2, 'role_id' => 1]);

        // logout and login with new user
        $response = $this->get('admin/auth/logout');
        $response->assertRedirect('admin/auth/login');
        $this->assertGuest('admin');
        
        $response = $this->post('admin/auth/login', ['username' => $user['username'], 'password' => $user['password']]);
        $response->assertRedirect('admin');
        $this->assertAuthenticated('admin');

        $this->assertTrue($this->app['auth']->guard('admin')->getUser()->isAdministrator());

        $response = $this->get('admin');
        $response->assertSee('<span>Users</span>', false)
            ->assertSee('<span>Roles</span>', false)
            ->assertSee('<span>Permission</span>', false)
            ->assertSee('<span>Operation log</span>', false)
            ->assertSee('<span>Menu</span>', false);
    }

    public function testUpdateUser()
    {
        $response = $this->get('admin/auth/users/'.$this->user->id.'/edit');
        $response->assertStatus(200)->assertSee('Create');
        
        $response = $this->put('admin/auth/users/'.$this->user->id, ['name' => 'test', 'roles' => [1]]);
        $response->assertRedirect('admin/auth/users');
        $this->assertDatabaseHas(config('admin.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'password'              => $password,
            'password_confirmation' => $password,
            'roles'                 => [1],
        ];

        $response = $this->get('admin/auth/users/'.$this->user->id.'/edit');
        $response->assertStatus(200)->assertSee('Create');
        
        $response = $this->put('admin/auth/users/'.$this->user->id, $data);
        $response->assertRedirect('admin/auth/users');
        
        $response = $this->get('admin/auth/logout');
        $response->assertRedirect('admin/auth/login');
        $this->assertGuest('admin');
        
        $response = $this->post('admin/auth/login', ['username' => $this->user->username, 'password' => $password]);
        $response->assertRedirect('admin');
        $this->assertAuthenticated('admin');
    }
}
