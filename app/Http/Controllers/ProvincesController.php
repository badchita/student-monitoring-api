<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProvincesResource;
use App\Models\Provinces;
use Illuminate\Http\Request;

class ProvincesController extends Controller
{
    public function getAllProvinces(Request $request)
    {
        $country_id = $request->countryCode;
        $provinces = Provinces::select('*')->where('country_id', $country_id)->get();

        return ProvincesResource::collection($provinces);
    }
}
