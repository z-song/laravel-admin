<?php

/**
 * Created by PhpStorm.
 * User: encore
 * Date: 16/11/20
 * Time: 下午6:44.
 */
class InstallTest extends TestCase
{
    public function testInstalledDirectories()
    {
        $this->assertFileExists(admin_path());

        $this->assertFileExists(admin_path('Controllers'));

        $this->assertFileExists(admin_path('routes.php'));

        $this->assertFileExists(admin_path('bootstrap.php'));

        $this->assertFileExists(admin_path('Controllers/HomeController.php'));

        $this->assertFileExists(admin_path('Controllers/ExampleController.php'));

        $this->assertFileExists(config_path('admin.php'));

        $this->assertFileExists(public_path('vendor/laravel-admin'));
    }
}
