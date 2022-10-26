<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountriesResource;
use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function getAllCountries()
    {
        $countries = Countries::select('*')->get();

        return CountriesResource::collection($countries);
    }
}
