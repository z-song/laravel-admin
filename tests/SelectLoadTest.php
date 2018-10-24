<?php

use Encore\Admin\Auth\Database\Administrator;

class SelectLoadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }


    public function testLoadSelect()
    {
        $this->seedsTable($count = 10);

        $person = \Tests\Models\Person::query()->first();
        $city = \Tests\Models\City::query()->find($person->city_id);


        $this->visit('admin/api/countries')->seeJson();
        $this->visit('admin/api/cities')->seeJson();


        $this->visit("admin/persons/{$person->getKey()}/edit")
             ->see("data-value=\"{$city->getKey()}\"");
             // ->see("title=\"{$city->text}\"");

    }


    protected function seedsTable($count = 100)
    {
        factory(\Tests\Models\Country::class, $count)
            ->create()
            ->each(function ($country) {

                factory(\Tests\Models\City::class, 5)->create(['country_id' => $country->id]);
            });

        $countryKey = \Tests\Models\Country::query()->inRandomOrder()->first()->getKey();
        $cityKey = \Tests\Models\City::query()->inRandomOrder()->first()->getKey();
        factory(\Tests\Models\Person::class)->create(['country_id' => $countryKey, 'city_id' => $cityKey]);
    }
}
