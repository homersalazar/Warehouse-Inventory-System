<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(){
        $activated_area = Area::whereArea_status(0)->get();
        $deactivated_area = Area::whereArea_status(1)->get();
        $deactivated_count = Area::whereArea_status(1)->count();
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
            'area_name' => 'required|unique:areas,area_name,' . $id 
        ]);
        
        if ($validated) {
            $area = Area::find($id);
            
            if (!$area) {
                return redirect()->back()->with('error', 'Area not found.');
            }
            
            $areaIsExist = Area::where('area_name', ucwords($request->input('area_name')))
                ->where('id', '!=', $id) 
                ->first();
    
            if ($areaIsExist) {
                return redirect()->back()->with('error', 'The area is already exist.');
            }
            
            $area->area_name = ucwords($request->input('area_name'));
            if ($area->save()) {
                return redirect()->route('area.index')
                    ->with('success', ucwords($request->input('area_name')) . ' has been updated successfully.');
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


    public function autocomplete(Request $request){
        if($request->get('query')) {
            $query = $request->get('query');
            $output = '';
            $data = Area::where('area_name' ,'LIKE', "%{$query}%")
                ->where('area_status', 0)
                ->limit(10)
                ->get();
            if(count($data) > 0) {
                $output .= '<ul class="max-w-full sm:w-[24rem] divide-y bg-gray-700 divide-gray-600 border p-3 text-gray-700 dark:text-gray-400 rounded-lg shadow absolute z-1">';
                    foreach ($data as $row) {
                        $output .= '<li onclick="fill_area(\''.$row->id .'\' , \''.$row->area_name.'\')" class="hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white p-1">' .$row->area_name .'</li>';
                    }
                $output .=  '</ul>';
            } else {
                $output .= '<ul class="max-w-full sm:w-[24rem] divide-y bg-gray-700 divide-gray-600 border p-3 text-gray-700 dark:text-gray-400 rounded-lg shadow absolute z-1">';
                $output .= '<li class="p-1">No matching area found in the database. <br> Input noted for future reference.</li>';
                $output .= '</ul>';
            }
            return $output;
            return view('product.add_new_inventory');
        }
    }
}
