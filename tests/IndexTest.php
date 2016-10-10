<?php

use Encore\Admin\Auth\Database\Administrator;

class IndexTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testIndex()
    {
        $this->visit('admin/')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Roles')
            ->see('Permission')
            ->see('Menu')
            ->see('1024')
            ->see('150%')
            ->see('2786')
            ->see('698726')
            ->see('Tabs')
            ->see('Radar')
            ->see('Bar')
            ->see('Orders')
            ->see('Polar Area')
            ->see('Doughnut')
            ->see('Line')
            ->see('Table')
            ->see('Email')
            ->see('Last Login')
            ->see('Copyright')
            ->see('Version');
    }

    public function testClickMenu()
    {
        $this->visit('admin/')
            ->click('Users')
            ->seePageis('admin/auth/users')
            ->click('Roles')
            ->seePageis('admin/auth/roles')
            ->click('Permission')
            ->seePageis('admin/auth/permissions')
            ->click('Menu')
            ->seePageis('admin/auth/menu');
    }
}
