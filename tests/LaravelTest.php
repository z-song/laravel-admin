<?php

class LaravelTest extends TestCase
{
    public function testLaravel()
    {
        $this->visit('/')
            ->see('Laravel 5');
    }
}
