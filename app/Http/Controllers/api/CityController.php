<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function get_city_streets(Cities $city)
    {
        return response()->data(
            $city->street()->get()
        );
    }
}
