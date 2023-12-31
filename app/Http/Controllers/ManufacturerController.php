<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(){
        $activated_manufacturer = Manufacturer::whereManufacturer_status(0)->get();        
        $deactivated_manufacturer = Manufacturer::whereManufacturer_status(1)->get();
        $deactivated_count = Manufacturer::whereManufacturer_status(1)->count();        
        return view('manufacturer.index', 
        compact('activated_manufacturer', 'deactivated_manufacturer', 'deactivated_count'));    
    }

    public function create(){
        return view('manufacturer.create');
    }

    public function store(Request $request){
        $validate = $request->validate([
            'manufacturer_name' => 'required|unique:manufacturers,manufacturer_name'
        ]);
        
        if ($validate) {
            $manufacturer = Manufacturer::firstOrCreate([
                'manufacturer_name' => ucwords($request->manufacturer_name),
                'manufacturer_status' => 0, // 0 - active, 1 - deactivated
            ]);

            if ($manufacturer->wasRecentlyCreated) {
                return redirect()->route('manufacturer.index')
                ->with('success', ucwords($request->manufacturer_name).' has been created successfully.');
            } else {
                return redirect()->back()
                ->with('error', ucwords($request->manufacturer_name).' already exists.');
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
            'manufacturer_name' => 'required|unique:manufacturers,manufacturer_name,' . $id 
        ]);
        
        if ($validated) {
            $manufacturer = Manufacturer::find($id);
            
            if (!$manufacturer) {
                return redirect()->back()->with('error', 'Manufacturer not found.');
            }
            
            $manufacturerIsExist = Manufacturer::where('manufacturer_name', ucwords($request->input('manufacturer_name')))
                ->where('id', '!=', $id) 
                ->first();
    
            if ($manufacturerIsExist) {
                return redirect()->back()->with('error', 'The manufacturer is already exist.');
            }
            
            $manufacturer->manufacturer_name = ucwords($request->input('manufacturer_name'));
            if ($manufacturer->save()) {
                return redirect()->route('manufacturer.index')
                    ->with('success', ucwords($request->input('manufacturer_name')) . ' has been updated successfully.');
            } else {
                return redirect()->back()->with('error', 'An error occurred while updating the manufacturer.');
            }
        }
    }

    public function deactivate(Request $request, $id)
    {
        $disabled = 1;
        $manufacturer = Manufacturer::find($id);
        $manufacturer->manufacturer_status = $disabled;
        $manufacturer->save();
        return redirect()->route('manufacturer.index')
        ->with('success', ucwords($request->manufacturer_name).' has been updated successfully.');
    }

    public function reactivate(Request $request, $id)
    {
        $enabled = 0;
        $manufacturer = Manufacturer::find($id);
        $manufacturer->manufacturer_status = $enabled;
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
                $output .= '<li class="p-1">No matching brand found in the database. <br> Input noted for future reference.</li>';
                $output .= '</ul>';
            }
            return $output;
            return view('product.add_new_inventory');
        }
    }
}
