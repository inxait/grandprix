<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function getCitiesInDepartment($id)
    {
        $cities = City::where('department_id', $id)->get();

        return response()->json(['success' => true, 'info' => $cities]);
    }
}
