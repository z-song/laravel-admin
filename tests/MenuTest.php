<?php

use Encore\Admin\Models\User;
use Encore\Admin\Models\Menu;

class MenuTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(User::first(), 'admin');
    }

    public function testMenuIndex()
    {
        $this->visit('admin/auth_menus')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Menu');
    }

    public function testAddMenu()
    {
        $item = ['parent_id' => '0', 'title' => 'Test', 'uri' => 'test'];

        $this->visit('admin/auth_menus')
            ->seePageIs('admin/auth_menus')
            ->see('Menu')
            ->submitForm('Submit', $item)
            ->seePageIs('admin/auth_menus')
            ->seeInDatabase(config('admin.database.menu_table'), $item)
            ->assertEquals(8, Menu::count());

//        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);
//
//        $this->visit('admin')
//            ->see('Test')
//            ->click('Test');
    }

    public function testDeleteMenu()
    {
        $this->delete('admin/auth_menus/8')
            ->assertEquals(7, Menu::count());
    }

    public function testEditMenu()
    {
        $this->visit('admin/auth_menus/1/edit')
            ->see('Menu')
            ->submitForm('Submit', ['title' => 'blablabla'])
            ->seePageIs('admin/auth_menus')
            ->seeInDatabase(config('admin.database.menu_table'), ['title' => 'blablabla'])
            ->assertEquals(7, Menu::count());
    }

    public function testShowPage()
    {
        $this->visit('admin/auth_menus/1/edit')
            ->seePageIs('admin/auth_menus/1/edit');
    }

    public function testEditMenuParent()
    {
        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('admin/auth_menus/5/edit')
            ->see('Menu')
            ->submitForm('Submit', ['parent_id' => 5]);
    }
}
