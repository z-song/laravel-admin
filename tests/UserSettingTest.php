<?php

use Encore\Admin\Models\User;
use Illuminate\Support\Facades\File;

class UserSettingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(User::first(), 'admin');
    }

    public function testVisitSettingPage()
    {
        $this->visit('admin/self_setting')
            ->see('User setting')
            ->see('Username')
            ->see('Name')
            ->see('Avatar')
            ->see('Password')
            ->see('Password confirmation');

        $this->seeElement('input[value=Administrator]')
            ->seeInElement('.box-body', 'administrator');
    }

    public function testUpdateName()
    {
        $data = [
            'name' => 'tester',
        ];

        $this->visit('admin/self_setting')
            ->submitForm('Submit', $data)
            ->seePageIs('admin/self_setting');

        $this->seeInDatabase('admin_users', ['name' => $data['name']]);
    }

    public function testUpdateAvatar()
    {
        File::cleanDirectory(public_path('uploads/images'));

        $this->visit('admin/self_setting')
            ->attach(__DIR__.'/assets/test.jpg', 'avatar')
            ->press('Submit')
            ->seePageIs('admin/self_setting');

        $avatar = User::first()->avatar;

        $this->assertEquals('http://localhost:8000/uploads/images/test.jpg', $avatar);
    }

    public function testUpdatePasswordConfirmation()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123',
        ];

        $this->visit('admin/self_setting')
            ->submitForm('Submit', $data)
            ->seePageIs('admin/self_setting')
            ->see('The Password confirmation does not match.');
    }

    public function testUpdatePassword()
    {
        $data = [
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('admin/self_setting')
            ->submitForm('Submit', $data)
            ->seePageIs('admin/self_setting');

        $this->assertTrue(app('hash')->check($data['password'], User::first()->makeVisible('password')->password));

        $this->visit('admin/auth/logout')
            ->seePageIs('admin/auth/login')
            ->dontSeeIsAuthenticated('admin');

        $credentials = ['username' => 'admin', 'password' => '123456'];

        $this->visit('admin/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'admin')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');
    }
}
