<?php

use Encore\Admin\Auth\Database\Administrator;
use Tests\Models\User as UserModel;
use Tests\Models\Profile as ProfileModel;

class GridTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testIndexPage()
    {
        $this->visit('admin/users')
            ->see('All users')
            ->seeInElement('tr th', 'Username')
            ->seeInElement('tr th', 'Email')
            ->seeInElement('tr th', 'Mobile')
            ->seeInElement('tr th', 'Full name')
            ->seeInElement('tr th', 'Avatar')
            ->seeInElement('tr th', 'Post code')
            ->seeInElement('tr th', 'Address')
            ->seeInElement('tr th', 'Position')
            ->seeInElement('tr th', 'Color')
            ->seeInElement('tr th', '开始时间')
            ->seeInElement('tr th', '结束时间')
            ->seeInElement('tr th', 'Color')
            ->seeInElement('tr th', 'Created at')
            ->seeInElement('tr th', 'Updated at');

        $this->seeElement('form[action="/admin/users"][method=get]')
            ->seeElement('form[action="/admin/users"][method=get] input[name=id]')
            ->seeElement('form[action="/admin/users"][method=get] input[name=username]')
            ->seeElement('form[action="/admin/users"][method=get] input[name=email]')
            ->seeElement('form[action="/admin/users"][method=get] input[name="profile[start_at][start]"]')
            ->seeElement('form[action="/admin/users"][method=get] input[name="profile[start_at][end]"]')
            ->seeElement('form[action="/admin/users"][method=get] input[name="profile[end_at][start]"]')
            ->seeElement('form[action="/admin/users"][method=get] input[name="profile[end_at][end]"]');

        $this->seeInElement('a[href="/admin/users?_export=1"]', 'Export')
            ->seeInElement('a[href="/admin/users/create"]', 'New');
    }

    protected function seedsTable($count = 100)
    {
        factory(\Tests\Models\User::class, $count)
            ->create()
            ->each(function($u) {
                $u->profile()->save(factory(\Tests\Models\Profile::class)->make());
            });
    }

    public function testGridWithData()
    {
        $this->seedsTable();

        $this->visit('admin/users')
            ->see('All users');

        $this->assertCount(100, UserModel::all());
        $this->assertCount(100, ProfileModel::all());

        $this->click(2)->seePageIs('admin/users?page=2');
        $this->click(3)->seePageIs('admin/users?page=3');
        $this->click(4)->seePageIs('admin/users?page=4');
        $this->click(5)->seePageIs('admin/users?page=5');
        $this->click(1)->seePageIs('admin/users?page=1');
    }

    public function testGridIdFileter()
    {
        $this->seedsTable(50);

        $this->visit('admin/users')
            ->see('All users');

        $this->assertCount(50, UserModel::all());
        $this->assertCount(50, ProfileModel::all());

        $id = rand(1, 50);

        $user = UserModel::find($id);

        $this->visit('admin/users?id='.$id)
            ->seeInElement('td', $user->username)
            ->seeInElement('td', $user->email)
            ->seeInElement('td', $user->mobile)
            ->seeElement("img[src='{$user->avatar}']")
            ->seeInElement('td', "{$user->profile->first_name} {$user->profile->last_name}")
            ->seeInElement('td', $user->postcode)
            ->seeInElement('td', $user->address)
            ->seeInElement('td', "{$user->profile->latitude} {$user->profile->longitude}")
            ->seeInElement('td', $user->color)
            ->seeInElement('td', $user->start_at)
            ->seeInElement('td', $user->end_at);
    }

    public function testGridLikeFilter()
    {
        $this->seedsTable(50);

        $this->visit('admin/users')
            ->see('All users');

        $this->assertCount(50, UserModel::all());
        $this->assertCount(50, ProfileModel::all());

        $users = UserModel::where('username', 'like', '%mi%')->get();

        $this->visit('admin/users?username=mi');

        $this->assertCount($this->crawler()->filter('table tr')->count()-1, $users);

        foreach ($users as $user) {
            $this->seeInElement('td', $user->username);
        }
    }

    public function testCreateUser()
    {
        $this->markTestIncomplete();

        $this->visit('admin/users')
            ->click('New')
            ->seePageIs('admin/users/create');
    }
}
