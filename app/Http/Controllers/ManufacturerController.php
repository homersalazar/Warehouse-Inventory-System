<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(){
        $activated_manufacturer = Manufacturer::where('manufacturer_status', '=' , 0)->get();        
        $deactivated_manufacturer = Manufacturer::where('manufacturer_status', '=' , 1)->get();
        $deactivated_count = Manufacturer::where('manufacturer_status', '=', 1)->count();        
        return view('manufacturer.index', compact('activated_manufacturer', 'deactivated_manufacturer', 'deactivated_count'));    
    }

    public function create(){
        return view('manufacturer.create');
    }

    public function store(Request $request){
        $validate = $request->validate([
            'manufacturer_name' => 'required|unique:manufacturers,manufacturer_name'
        ]);
        
        if ($validate) {
            $location = Manufacturer::firstOrCreate([
                'manufacturer_name' => ucwords($request->manufacturer_name),
                'manufacturer_status' => 0, // 0 - active, 1 - deactivated
            ]);

            if ($location->wasRecentlyCreated) {
                return redirect()->route('manufacturer.index')
                ->with('success', ucwords($request->manufacturer_name).' has been created successfully.');
            } else {
                return redirect()->back()
                ->with('success', ucwords($request->manufacturer_name).' already exists.');
            }
        }
    }

    public function edit($id)
    {
        $manufacturer = Manufacturer::find($id);
        return view('manufacturer.edit', compact('manufacturer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'manufacturer_name' => 'required'
        ]);
        
        if ($validated) {
            $manufacturer = Manufacturer::find($id);
            if (!$manufacturer) {
                return redirect()->back()
                    ->with('error', 'Location not found.');
            }
            $manufacturer->manufacturer_name = ucwords($request->manufacturer_name);
            if ($manufacturer->save()) {
                return redirect()->route('manufacturer.index')
                    ->with('success', ucwords($request->manufacturer_name).' has been updated successfully.');
            } else {
                return redirect()->back()
                    ->with('error', 'An error occurred while updating the manufacturer.');
            }
        }
    }

    public function deactivate(Request $request, $id)
    {
        $manufacturer = Manufacturer::find($id);
        $manufacturer->manufacturer_status = 1;
        $manufacturer->save();
        return redirect()->route('manufacturer.index')
        ->with('success', ucwords($request->manufacturer_name).' has been updated successfully.');
    }

    public function reactivate(Request $request, $id)
    {
        $manufacturer = Manufacturer::find($id);
        $manufacturer->manufacturer_status = 0;
        $manufacturer->save();
        return redirect()->route('manufacturer.index')
        ->with('success', ucwords($request->manufacturer_name).' has been updated successfully.');
    }

}
