<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Show the form with states and cities
    public function showForm()
    {
        // Fetch all states in Nigeria
        $nigeria = Country::where('name', 'Nigeria')->first();
        $states = State::where('country_id', $nigeria->id)->get();

        return view('location-form', compact('states'));
    }

    // Fetch cities based on the selected state
    public function getCities(Request $request)
    {
        $stateName = $request->state_name;  // Get the selected state name from the request

        // Fetch the state by name
        $state = \App\Models\State::where('name', $stateName)->first();

        if ($state) {
            // Fetch cities based on the state ID
            $cities = City::where('state_id', $state->id)->get();
            return response()->json($cities);
        }

        return response()->json([]);
    }
}
