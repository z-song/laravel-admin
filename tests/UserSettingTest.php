<?php

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\File;

class UserSettingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testVisitSettingPage()
    {
        $response = $this->get('admin/auth/setting');

        $response->assertStatus(200)
            ->assertSee('User setting')
            ->assertSee('Username')
            ->assertSee('Name')
            ->assertSee('Avatar')
            ->assertSee('Password')
            ->assertSee('Password confirmation')
            ->assertSee('value="Administrator"', false)
            ->assertSee('administrator');
    }

    public function testUpdateName()
    {
        $data = [
            'name' => 'tester',
        ];

        $response = $this->put('admin/auth/setting', $data);

        $response->assertRedirect('admin/auth/setting');

        $this->assertDatabaseHas('admin_users', ['name' => $data['name']]);
    }

    public function testUpdateAvatar()
    {
        File::cleanDirectory(public_path('uploads/images'));

        $response = $this->call('PUT', 'admin/auth/setting', [], [], [
            'avatar' => new \Illuminate\Http\UploadedFile(
                __DIR__.'/assets/test.jpg',
                'test.jpg',
                'image/jpeg',
                null,
                true
            )
        ]);

        $response->assertRedirect('admin/auth/setting');

        $avatar = Administrator::first()->avatar;

        $this->assertEquals('http://localhost:8000/uploads/images/test.jpg', $avatar);
    }

    public function testUpdatePasswordConfirmation()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123',
        ];

        $response = $this->put('admin/auth/setting', $data);

        $response->assertRedirect('admin/auth/setting')
            ->assertSessionHasErrors(['password']);
    }

    public function testUpdatePassword()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $response = $this->put('admin/auth/setting', $data);

        $response->assertRedirect('admin/auth/setting');

        $this->assertTrue(app('hash')->check($data['password'], Administrator::first()->makeVisible('password')->password));

        // Test logout
        $response = $this->get('admin/auth/logout');
        $response->assertRedirect('admin/auth/login');
        $this->assertGuest('admin');

        // Test login with new password
        $credentials = ['username' => 'admin', 'password' => '123456'];

        $response = $this->post('admin/auth/login', $credentials);
        $response->assertRedirect('admin');
        $this->assertAuthenticatedAs(Administrator::first(), 'admin');
    }
}
