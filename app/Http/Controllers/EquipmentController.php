<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipments = Equipment::all();
        return view('equipment.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('equipment.create');
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
            'equip_unit' => 'required|unique:equipment,equip_unit'
        ]);
        
        if ($validate) {
            $equipment = Equipment::firstOrCreate([
                'equip_unit' => strtoupper($request->equip_unit),
            ]);

            if ($equipment->wasRecentlyCreated) {
                return redirect()->route('equipment.index')
                ->with('success', ucwords($request->equip_unit).' has been created successfully.');
            } else {
                return redirect()->back()
                ->with('error', ucwords($request->equip_unit).' already exists.');
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
        $equipment = Equipment::find($id);
        return view('equipment.edit', compact('equipment'));
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
            'equip_unit' => 'required|unique:equipment,equip_unit,' . $id 
        ]);
        
        if ($validated) {
            $equipment = Equipment::find($id);
            
            if (!$equipment) {
                return redirect()->back()->with('error', 'Equipment not found.');
            }
            
            $equipmentIsExist = Equipment::where('equip_unit', ucwords($request->input('equip_unit')))
                ->where('id', '!=', $id) 
                ->first();
    
            if ($equipmentIsExist) {
                return redirect()->back()->with('error', 'The equipment is already exist.');
            }
            
            $equipment->equip_unit = ucwords($request->input('equip_unit'));
            if ($equipment->save()) {
                return redirect()->route('equipment.index')
                    ->with('success', ucwords($request->input('equip_unit')) . ' has been updated successfully.');
            } else {
                return redirect()->back()->with('error', 'An error occurred while updating the equipment.');
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
        //
    }
}
