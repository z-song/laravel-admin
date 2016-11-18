<?php

class AuthTest extends TestCase
{
    public function testLoginPage()
    {
        $this->visit('admin/auth/login')
            ->see('login');
    }

    public function testVisitWithoutLogin()
    {
        $this->visit('admin')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/auth/login');
    }

    public function testLogin()
    {
        $credentials = ['username' => 'admin', 'password' => 'admin'];

        $this->visit('admin/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'admin')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin')
            ->see('Index')
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

        $this
            ->see('<span>Admin</span>')
            ->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testLogout()
    {
        $this->visit('admin/auth/logout')
            ->seePageIs('admin/auth/login')
            ->dontSeeIsAuthenticated('admin');
    }
}
