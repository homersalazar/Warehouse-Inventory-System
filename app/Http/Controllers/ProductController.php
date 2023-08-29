<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Label;
use App\Models\Manufacturer;
use App\Models\Preference;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function add_inventory(){
        return view('product.add_inventory');
    }

    public function add_new_inventory(){

        return view('product.add_new_inventory');
    }

    public function store(Request $request){
        $request->validate([
            'prod_name' => 'required|unique:products,prod_name'
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
        
        $transaction = Transaction::firstOrCreate([
            'product_id' => $product_ids,
            'tran_date' => $request->tran_date,
            'tran_option' => $request->tran_option,
            'tran_quantity' => $request->tran_quantity,
            'tran_unit' => $request->tran_unit,
            'tran_serial' => $request->tran_serial,
            'tran_comment' => $request->tran_comment,
            'tran_action' => 0,
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
            $data = Product::where('prod_name' ,'LIKE', "%{$query}%")->get();
            if(count($data)>0) {
                $output .= '<table class="table mt-3">
                    <tr>
                        <th>Name</th>
                        <th>Sku</th>
                        <th>Part #</th>
                        <th>Manufacturer</th>
                    </tr>
                </div>';
                foreach ($data as $row) {
                    $output .= '<tbody>
                        <tr>
                            <td><a href="">'.ucwords($row->prod_name).'</td>
                            <td>ghecc'.$row->prod_sku.'</a></td>
                            <td>'.$row->prod_upc.'</td>
                            <td>'.ucwords($row->manufacturer_id).'</td>
                        </tr>
                    </tbody>';
                }
            }else{
                $output .= '<hr>
                <div class="text-center">Item Not Found</div>
                <hr>';
            }
            $output .= '</div>';
            return $output;
            return view('product.add_inventory');
        }
    }

    
}
