<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->orderBy('name')->get(['id', 'name']);
        return response()->json($cities);
    }
}
