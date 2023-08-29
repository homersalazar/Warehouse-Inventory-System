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

    public function autocomplete(Request $request){
        if($request->get('query')) {
            $query = $request->get('query');
            $output = '';
            $data = Manufacturer::where('manufacturer_name' ,'LIKE', "%{$query}%")
                ->where('manufacturer_status', 0)
                ->limit(10)
                ->get();
            if(count($data) > 0) {
                $output .= '<ul class="max-w-full sm:w-[24rem] divide-y bg-gray-700 divide-gray-600 border p-3 text-gray-700 dark:text-gray-400 rounded-lg shadow absolute z-1">';
                    foreach ($data as $row) {
                        $output .= '<li onclick="fill_manufacturer(\''.$row->id .'\' , \''.$row->manufacturer_name.'\')" class="hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white p-1">' .$row->manufacturer_name .'</li>';
                    }
                $output .=  '</ul>';
            } else {
                $output .= '<ul class="max-w-full sm:w-[24rem] divide-y bg-gray-700 divide-gray-600 border p-3 text-gray-700 dark:text-gray-400 rounded-lg shadow absolute z-1">';
                $output .= '<li class="p-1">No Item found . <br> This manufacturer will be added in database</li>';
                $output .= '</ul>';
            }
            return $output;
            return view('product.add_new_inventory');
        }
    }
}
