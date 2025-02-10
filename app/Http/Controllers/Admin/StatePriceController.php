<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatePrice;
use Illuminate\Http\Request;

class StatePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statePrices = StatePrice::all();
        return view('admin.stateprices', compact('statePrices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin'      => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price'       => 'required|integer|min:0',
        ]);

        $statePrice = StatePrice::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'State price created successfully!',
            'data'    => $statePrice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'origin'      => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price'       => 'required|integer|min:0',
        ]);

        $statePrice = StatePrice::findOrFail($id);
        $statePrice->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'State price updated successfully!',
            'data'    => $statePrice,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $statePrice = StatePrice::findOrFail($id);
        $statePrice->delete();

        return response()->json([
            'success' => true,
            'message' => 'State price deleted successfully!',
        ]);
    }
}
