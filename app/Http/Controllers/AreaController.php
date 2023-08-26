<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(){
        $activated_area = Area::where('area_status', '=' , 0)->get();        
        $deactivated_area = Area::where('area_status', '=' , 1)->get();
        $deactivated_count = Area::where('area_status', '=', 1)->count();        
        return view('area.index', compact('activated_area', 'deactivated_area', 'deactivated_count'));    
    }

    public function create(){
        return view('area.create');
    }

    public function store(Request $request){
        $validate = $request->validate([
            'area_name' => 'required|unique:areas,area_name'
        ]);
        
        if ($validate) {
            $location = Area::firstOrCreate([
                'area_name' => ucwords($request->area_name),
                'area_status' => 0, // 0 - active, 1 - deactivated
            ]);

            if ($location->wasRecentlyCreated) {
                return redirect()->route('area.index')
                ->with('success', ucwords($request->area_name).' has been created successfully.');
            } else {
                return redirect()->back()
                ->with('success', ucwords($request->area_name).' already exists.');
            }
        }
    }

    public function edit($id)
    {
        $area = Area::find($id);
        return view('area.edit', compact('area'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'area_name' => 'required'
        ]);
        
        if ($validated) {
            $area = Area::find($id);
            if (!$area) {
                return redirect()->back()
                    ->with('error', 'Location not found.');
            }
            $area->area_name = ucwords($request->area_name);
            if ($area->save()) {
                return redirect()->route('area.index')
                    ->with('success', ucwords($request->area_name).' has been updated successfully.');
            } else {
                return redirect()->back()
                    ->with('error', 'An error occurred while updating the area.');
            }
        }
    }

    public function deactivate(Request $request, $id)
    {
        $area = Area::find($id);
        $area->area_status = 1;
        $area->save();
        return redirect()->route('area.index')
        ->with('success', ucwords($request->area_name).' has been updated successfully.');
    }

    public function reactivate(Request $request, $id)
    {
        $area = Area::find($id);
        $area->area_status = 0;
        $area->save();
        return redirect()->route('area.index')
        ->with('success', ucwords($request->area_name).' has been updated successfully.');
    }
}
