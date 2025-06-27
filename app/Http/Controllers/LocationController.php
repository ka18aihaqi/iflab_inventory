<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Location::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $locations = $query->paginate(5, ['*'], 'computer_page');

        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:locations|max:255',
            'description' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->with('error', "<strong>$request->name</strong> has already been taken.");
        }
    
        Location::create($request->all());
    
        return redirect()->route('locations.index')->with('success', "<strong>$request->name</strong> added successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:locations,name,' . $location->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', "<strong>$request->name</strong> has already been taken.");
        }

        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', "<strong>$request->name</strong> has been successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', "<strong>$location->name</strong> deleted successfully.");
    }
}
