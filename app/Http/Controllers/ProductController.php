<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Label;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Preference;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{   
    public function add_inventory(){
        return view('product.add_inventory');
    }

    public function product_autocomplete(Request $request)
    {
        $loc_id = session('role') == 0 ? $request->get('loc_id') : null;
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = Product::where('prod_name', 'LIKE', "%{$query}%")
                ->orWhere('prod_sku', 'LIKE', "%{$query}%")
                ->get();

            if (count($data) > 0) {
                $output = '<table class="w-full text-sm text-left">
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
                foreach ($data as $row) {
                    $output .= '<tr class="border-b px-6">
                                    <td class="p-2 px-6 font-medium text-gray-900 whitespace-nowrap underline text-blue-800 cursor-pointer">';
                    if (session("role") == 0) {
                        $output .= '<a href="'.route('transaction.edit', ['id' => $row->id, 'loc_id' => $loc_id]).'">'
                                        .strtoupper($row->prod_name).
                                    '</a>';
                    } else {
                        $output .= '<a href="'.route('transaction.show', $row->prod_sku).'">
                                        '.ucwords($row->prod_name).'
                                    </a>';
                    }
                    $output .= '</td>
                                <td class="px-6">
                                    SKU0'.$row->prod_sku.'
                                </td>
                                <td class="px-6 max-sm:hidden">
                                    '.strtoupper($row->prod_partno).'
                                </td>
                                <td class="px-6">
                                    '.ucwords($row->manufacturer->manufacturer_name).'
                                </td>
                            </tr>';
                }
                $output .= '</tbody></table>';
            } else {
                $output = '<div class="text-center border-t border-b text-base p-2">No Item found</div>';
            }
            return $output;
        }
    }

    // add new inventory
    public function add_new_inventory(){
        $location = Location::all();
        return view('product.add_new_inventory', compact('location'));
    }

    public function store(Request $request){
        $request->validate([
            'prod_name' => 'required|unique:products,prod_name',
            'tran_quantity' => 'required'
        ]);

        if (empty($request->label_id) && !empty($request->label_name)) {
            $label = Label::updateOrCreate([
                'label_name' => ucwords($request->label_name)
            ]);
            $label->save();
            $labels_id = $label->id;
        }

        if (empty($request->manufacturer_id) && !empty($request->manufacturer_name)) {
            $manufacturer = Manufacturer::updateOrCreate([
                'manufacturer_name' => ucwords($request->manufacturer_name),
                'manufacturer_status' => 0,
            ]);
            $manufacturer->save();
            $manufacturers_id = $manufacturer->id;
        }

        if (empty($request->unit_id) && !empty($request->unit_name)) {
            $unit = Unit::updateOrCreate([
                'unit_name' => ucwords($request->unit_name)
            ]);
            $unit->save();
            $units_id = $unit->id;
        }

        $pref = Preference::whereId(1)->first();
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
            'prod_partno' => $request->prod_partno,
            'unit_id' => !empty($request->unit_id) ?  $request->unit_id : $units_id,
            'label_id' => !empty($request->label_id) ?  $request->label_id : $labels_id,
            'manufacturer_id' => !empty($request->manufacturer_id) ?  $request->manufacturer_id : $manufacturers_id,
            'pref_id' => $min
        ]);
        $product->save();

        if (empty($request->area_id) && !empty($request->area_name)) {
            $area = Area::updateOrCreate([
                'area_name' => strtoupper($request->area_name),
                'area_status' => 0,
            ]);
            $area->save();
            $areas_id = $area->id;
        }
        $location_id = session('role') == 0 ? $request->universal_location : auth()->user()->location_id;
        $addAction = 0;
        $transaction = Transaction::firstOrCreate([
            'prod_sku' => $sku_id,
            'tran_date' => $request->tran_date,
            'tran_option' => $request->tran_option,
            'tran_quantity' => $request->tran_quantity,
            'tran_unit' => $request->tran_unit,
            'tran_serial' => $request->tran_serial,
            'tran_remarks' => $request->tran_remarks,
            'tran_action' => $addAction,
            'area_id' => !empty($request->area_id) ?  $request->area_id : $areas_id,
            'location_id' => $location_id,
            'tran_remarks' => $request->tran_remarks,
            'user_id' => Auth::user()->id
        ]);
        $transaction->save();
        return redirect()->back()
        ->with('success', 'New product added successfully.');
    }

    // ITEM EDIT, UPDATE
    public function edit($id)
    {
        $product = Product::find($id);
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'prod_name' => 'required|unique:products,prod_name',
        ]);

        if (empty($request->label_id) && !empty($request->label_name)) {
            $label = Label::updateOrCreate([
                'label_name' => ucwords($request->label_name)
            ]);
            $label->save();
            $labels_id = $label->id;
        }
        if (empty($request->unit_id) && !empty($request->unit_name)) {
            $unit = Unit::updateOrCreate([
                'unit_name' => ucwords($request->unit_name)
            ]);
            $unit->save();
            $units_id = $unit->id;
        }

        if (empty($request->manufacturer_id) && !empty($request->manufacturer_name)) {
            $manufacturer = Manufacturer::updateOrCreate([
                'manufacturer_name' => ucwords($request->manufacturer_name),
                'manufacturer_status' => 0,
            ]);
            $manufacturer->save();
            $manufacturers_id = $manufacturer->id;
        }
        $product = Product::updateOrInsert(
        ['id' => $id],
        [
            'prod_name' => $request->prod_name,
            'prod_partno' => $request->prod_partno,
            'label_id' => !empty($request->label_id) ?  $request->label_id : $labels_id,
            'unit_id' => !empty($request->unit_id) ? $request->unit_id : $units_id,
            'manufacturer_id' => !empty($request->manufacturer_id) ?  $request->manufacturer_id : $manufacturers_id,
        ]);
        return redirect()->route('product.add_inventory')
        ->with('success', 'Product updated successfully.');
    }

    // Stock Out
    public function remove_inventory(){
        return view('product.remove_inventory');
    }

    public function remove_autocomplete(Request $request)
    {
        $loc_id = session('role') == 0 ? $request->get('loc_id') : null;
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = Product::where('prod_name', 'LIKE', "%{$query}%")
                ->orWhere('prod_sku', 'LIKE', "%{$query}%")
                ->get();

            if (count($data) > 0) {
                $output = '<table class="w-full text-sm text-left">
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
    
                foreach ($data as $row) {
                    $output .= '<tr class="border-b px-6">
                                    <td class="p-2 px-6 font-medium text-gray-900 whitespace-nowrap underline text-blue-800 cursor-pointer">';
                    if (session("role") == 0) {
                        $output .= '<a href="'.route('transaction.remove_edit', ['id' => $row->id, 'loc_id' => $loc_id]).'">'
                                        .ucwords($row->prod_name).
                                    '</a>';
                    } else {
                        $output .= '<a href="'.route('transaction.remove_show', $row->id).'">
                                        '.ucwords($row->prod_name).'
                                    </a>';
                    }
                    $output .= '</td>
                                <td class="px-6">
                                    SKU0'.$row->prod_sku.'
                                </td>
                                <td class="px-6 max-sm:hidden">
                                    '.ucwords($row->prod_partno).'
                                </td>
                                <td class="px-6">
                                    '.ucwords($row->manufacturer->manufacturer_name).'
                                </td>
                            </tr>';
                }
    
                $output .= '</tbody></table>';
            } else {
                $output = '<div class="text-center border-t border-b text-base p-2">No Item found</div>';
            }
    
            // You should only return the $output here, not view('product.add_inventory');
            return $output;
        }
    }
}
