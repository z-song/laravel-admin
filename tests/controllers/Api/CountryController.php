<?php

namespace Tests\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tests\Models\City;
use Tests\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        return Country::query()->pluck('text', 'id');
    }

    public function getCities(Request $request)
    {
        return City::query()->where('country_id', $request->input('q'))->pluck('text', 'id');
    }
}
