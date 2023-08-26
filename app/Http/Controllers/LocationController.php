<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::all();
        return view('location.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'loc_name' => 'required|unique:locations,loc_name'
        ]);
        
        if ($validate) {
            $location = Location::firstOrCreate([
                'loc_name' => ucwords($request->loc_name),
                'loc_address' => ucwords($request->loc_address),
                'loc_city' => ucwords($request->loc_city),
                'loc_state' => ucwords($request->loc_state),
                'loc_zip' => $request->loc_zip,
            ]);

            if ($location->wasRecentlyCreated) {
                return redirect()->route('location.index')
                ->with('success', ucwords($request->loc_name).' has been created successfully.');
            } else {
                return redirect()->back()
                ->with('success', ucwords($request->loc_name).' already exists.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $location = Location::find($id);
        return view('location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'loc_name' => 'required'
        ]);
        
        if ($validated) {
            $location = Location::find($id);
            if (!$location) {
                return redirect()->back()
                    ->with('error', 'Location not found.');
            }
            $location->loc_name = ucwords($request->loc_name);
            $location->loc_address = ucwords($request->loc_address);
            $location->loc_city = ucwords($request->loc_city);
            $location->loc_state = ucwords($request->loc_state);
            $location->loc_zip = $request->loc_zip;
        
            if ($location->save()) {
                return redirect()->route('location.index')
                    ->with('success', ucwords($request->loc_name).' has been updated successfully.');
            } else {
                return redirect()->back()
                    ->with('error', 'An error occurred while updating the location.');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::find($id);
        $location->delete();
        return redirect()->route('location.index')
        ->with('success', 'Location has been deleted successfully.');
    }
}
