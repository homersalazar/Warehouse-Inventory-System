<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }

    public function create()
    {
        return view('unit.create');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'unit_name' => 'required|unique:units,unit_name'
        ]);
        
        if ($validate) {
            $unit = Unit::firstOrCreate([
                'unit_name' => ucwords($request->unit_name),
            ]);

            if ($unit->wasRecentlyCreated) {
                return redirect()->route('unit.index')
                ->with('success', ucwords($request->unit_name).' has been created successfully.');
            } else {
                return redirect()->back()
                ->with('error', ucwords($request->unit_name).' already exists.');
            }
        }
    }

    public function edit($id)
    {
        $unit = Unit::find($id);
        return view('unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'unit_name' => 'required|unique:units,unit_name,' . $id 
        ]);
        
        if ($validated) {
            $unit = Unit::find($id);
            
            if (!$unit) {
                return redirect()->back()->with('error', 'Unit not found.');
            }
            
            $unitIsExist = Unit::where('unit_name', ucwords($request->input('unit_name')))
                ->where('id', '!=', $id) 
                ->first();
    
            if ($unitIsExist) {
                return redirect()->back()->with('error', 'The unit is already exist.');
            }
            
            $unit->unit_name = ucwords($request->input('unit_name'));
            if ($unit->save()) {
                return redirect()->route('unit.index')
                    ->with('success', ucwords($request->input('unit_name')) . ' has been updated successfully.');
            } else {
                return redirect()->back()->with('error', 'An error occurred while updating the unit.');
            }
        }
    }

    public function autocomplete(Request $request){
        if($request->get('query')) {
            $query = $request->get('query');
            $output = '';
            $data = Unit::where('unit_name' ,'LIKE', "%{$query}%")
                ->limit(10)
                ->get();
            if(count($data) > 0) {
                $output .= '<ul class="max-w-full sm:w-[24rem] divide-y bg-gray-700 divide-gray-600 border p-3 text-gray-700 dark:text-gray-400 rounded-lg shadow absolute z-1">';
                    foreach ($data as $row) {
                        $output .= '<li onclick="fill_unit(\''.$row->id .'\' , \''.$row->unit_name.'\')" class="hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white p-1">' .$row->unit_name .'</li>';
                    }
                $output .=  '</ul>';
            } else {
                $output .= '<ul class="max-w-full sm:w-[24rem] divide-y bg-gray-700 divide-gray-600 border p-3 text-gray-700 dark:text-gray-400 rounded-lg shadow absolute z-1">';
                $output .= '<li class="p-1">No matching unit found in the database. <br> Input noted for future reference.</li>';
                $output .= '</ul>';
            }
            return $output;
            return view('product.add_new_inventory');
        }
    }
}
