<?php

class UninstallTest extends TestCase
{
    public function testInstalledDirectories()
    {
        $this->markTestSkipped();

        $this->artisan('admin:uninstall');

        $this->assertFileNotExists(admin_path());

        $this->assertFileNotExists(admin_path('Controllers'));

        $this->assertFileNotExists(admin_path('routes.php'));

        $this->assertFileNotExists(admin_path('bootstrap.php'));

        $this->assertFileNotExists(admin_path('Controllers/HomeController.php'));

        $this->assertFileNotExists(admin_path('Controllers/ExampleController.php'));

        $this->assertFileNotExists(config_path('admin.php'));

        $this->assertFileNotExists(public_path('packages/admin'));
    }
}
