<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Label;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Preference;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function add_inventory(){
        return view('product.add_inventory');
    }

    public function add_new_inventory(){
        $location = Location::all();
        return view('product.add_new_inventory', compact('location'));
    }

    public function store(Request $request){
        $request->validate([
            'prod_name' => 'required|unique:products,prod_name',
            'tran_quantity' => 'required'
        ]);

        if($request->label_id == '' && $request->label_name != ''){
            $label = Label::updateOrCreate([
                'label_name' => ucwords($request->label_name)
            ]);
            $label->save();
            $labels_id = $label->id;
        }
        if($request->area_id == '' && $request->area_name != ''){
            $area = Area::updateOrCreate([
                'area_name' => ucwords($request->area_name),
                'area_status' => 0,
            ]);
            $area->save();
            $areas_id = $area->id;
        }
        if($request->manufacturer_id == '' && $request->manufacturer_name != ''){
            $manufacturer = Manufacturer::updateOrCreate([
                'manufacturer_name' => ucwords($request->manufacturer_name),
                'manufacturer_status' => 0,
            ]);
            $manufacturer->save();
            $manufacturers_id = $manufacturer->id;
        }
        $pref = Preference::where('id', 1)->first();
        $min = '';
        if($request->pref_id == 0){
            $min = $pref->pref_value;
        }else if($request->pref_id == 1){
            $min = $request->overrideInput; 
        }else if($request->pref_id == 2){
            $min;
        }
        $prod_skus = Product::latest('id')->value('id');
        $sku_id = $prod_skus != '' ? $prod_skus + 1 : 1;
        $product = Product::firstOrCreate([
            'prod_name' => $request->prod_name,
            'prod_sku' => $sku_id,
            'prod_upc' => $request->prod_upc,
            'prod_summary' => $request->prod_summary,
            'label_id' => $request->label_id != '' ?  $request->label_id : $labels_id,
            'area_id' => $request->area_id != '' ?  $request->area_id : $areas_id,
            'manufacturer_id' => $request->manufacturer_id != '' ?  $request->manufacturer_id : $manufacturers_id,
            'pref_id' => $min
        ]);
        $product->save();
        $product_ids = $product->id;
        $location_id = session('role') == 0 ? $request->loc_id : auth()->user()->location_id;
        $transaction = Transaction::firstOrCreate([
            'product_id' => $product_ids,
            'tran_date' => $request->tran_date,
            'tran_option' => $request->tran_option,
            'tran_quantity' => $request->tran_quantity,
            'tran_unit' => $request->tran_unit,
            'tran_serial' => $request->tran_serial,
            'tran_comment' => $request->tran_comment,
            'tran_action' => 0,
            'location_id' => $location_id,
            'user_id' => auth()->user()->id
        ]);
        $transaction->save();
        return redirect()->back()
        ->with('success', 'Product added successfully.');

    }

    public function product_autocomplete(Request $request){
        if($request->get('query')) {
            $query = $request->get('query');
            $output = '';
            $data = Product::where('prod_name' ,'LIKE', "%{$query}%")
            ->orWhere('prod_sku' ,'LIKE', "%{$query}%")
            ->get();
            if(count($data)>0) {
            $output .= '<table class="w-full text-sm text-left">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        SKU
                                    </th>
                                    <th scope="col" class="px-6 py-3 max-sm:hidden">
                                        Part Number
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Manufacturer
                                    </th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach ($data as $row){
                    $output .= '<tr class="border-b px-6">
                                    <td class="p-2 px-6 font-medium text-gray-900 whitespace-nowrap underline text-blue-800 cursor-pointer">
                                        <a href="'.route('transaction.edit', $row->id).'">
                                            '.ucwords($row->prod_name).'
                                        </a>
                                    </td>
                                    <td class="px-6">
                                        SKU0'.$row->prod_sku.'
                                    </td>
                                    <td class="px-6 max-sm:hidden">
                                        '.ucwords($row->prod_upc).'
                                    </td>
                                    <td class="px-6">
                                        '.ucwords($row->manufacturer->manufacturer_name).'
                                    </td>
                                </tr>';
                            }
            }else{
                $output .= '<div class="text-center border-t border-b text-base p-2"> No Item found </div>';
            }
            $output .= '</tbody>
            </table>';
            return $output;
            return view('product.add_inventory');
        }
    }

    
}
