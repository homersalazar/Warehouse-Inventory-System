<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
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
