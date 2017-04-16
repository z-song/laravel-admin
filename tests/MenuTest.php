<?php

class MenuTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $model = config('admin.database.users_model');
        $this->be($model::first(), 'admin');
    }

    public function testMenuIndex()
    {
        $this->visit('admin/auth/menu')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Roles')
            ->see('Permission')
            ->see('Menu');
    }

    public function testAddMenu()
    {
        $item = ['parent_id' => '0', 'title' => 'Test', 'uri' => 'test'];

        $menu_model = config('admin.database.menu_model');
        $this->visit('admin/auth/menu')
            ->seePageIs('admin/auth/menu')
            ->see('Menu')
            ->submitForm('Submit', $item)
            ->seePageIs('admin/auth/menu')
            ->seeInDatabase(config('admin.database.menu_table'), $item)
            ->assertEquals(12, $menu_model::count());

        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('admin')
            ->see('Test')
            ->click('Test');
    }

    public function testDeleteMenu()
    {
        $menu_model = config('admin.database.menu_model');
        $this->delete('admin/auth/menu/8')
            ->assertEquals(7, $menu_model::count());
    }

    public function testEditMenu()
    {
        $menu_model = config('admin.database.menu_model');
        $this->visit('admin/auth/menu/1/edit')
            ->see('Menu')
            ->submitForm('Submit', ['title' => 'blablabla'])
            ->seePageIs('admin/auth/menu')
            ->seeInDatabase(config('admin.database.menu_table'), ['title' => 'blablabla'])
            ->assertEquals(11, $menu_model::count());
    }

    public function testShowPage()
    {
        $this->visit('admin/auth/menu/1')
            ->seePageIs('admin/auth/menu/1/edit');
    }

    public function testEditMenuParent()
    {
        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('admin/auth/menu/5/edit')
            ->see('Menu')
            ->submitForm('Submit', ['parent_id' => 5]);
    }
}
