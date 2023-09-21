<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Equipment;
use App\Models\Location;
use App\Models\Pending;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{   
    // user 
    public function show($id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $transactionAdd = Transaction::where('location_id', $user->location->id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [0, 2])
            ->sum('tran_quantity');
        $transactionRemove = Transaction::where('location_id', $user->location->id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [1, 3, 4, 5])
            ->sum('tran_quantity');
        $transferAdd = Pending::where('tran_from', $user->location->id)
            ->where('prod_sku', $product->prod_sku)
            ->sum('tran_quantity');
        $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
        $total = $total_stock <= 0 ? 0 : $total_stock;
        $transactionArea = Transaction::where('prod_sku', $id)
            ->where('location_id', $user->location->id)   
            ->whereNotNull('area_id')
            ->limit(1)
            ->oldest()
            ->get();
        return view('transaction.edit', compact('product', 'user', 'total', 'transactionArea'));  
    }

    public function store(Request $request){
        $request->validate([
            'tran_quantity' => 'required|numeric'
        ]);

        $addAction = 0;
        $area = null;

        $transactionArea = Transaction::where('prod_sku', $request->prod_sku)
            ->where('location_id', Auth::user()->location_id)
            ->whereNotNull('area_id')
            ->limit(1)
            ->oldest()
            ->get();
    
        if ($transactionArea && is_null($transactionArea)) {
            if (!empty($request->area_name)) {
                $area = Area::updateOrCreate(
                    [
                        'area_name' => strtoupper($request->area_name),
                        'area_status' => 0,
                    ]
                );
                $areas_id = $area->id;
            }
        }

        if (empty($request->area_name) && empty($request->area_id)){
            $area = NULL;
        }elseif (!empty($request->area_id)){
            $area = $request->area_id;
        }else{
            $area = $areas_id;
        }
        $transaction = Transaction::updateOrCreate([
            'prod_sku' => $request->prod_sku,
            'tran_date' => $request->tran_date,
            'tran_option' => $request->tran_option,
            'tran_quantity' => $request->tran_quantity,
            'tran_unit' => $request->tran_unit,
            'area_id' => $area,
            'tran_drno' => $request->tran_drno,
            'tran_mpr' => $request->tran_mpr,
            'tran_serial' => $request->tran_serial,
            'tran_remarks' => $request->tran_remarks,
            'tran_action' => $addAction,
            'location_id' => $request->location_id,
            'user_id' => Auth::user()->id
        ]);
        $transaction->save();
        if(session('role') == 0){
            return redirect()
                ->route('transaction.item', ['id' => $request->prod_sku, 'loc_id' => $request->location_id])
                ->with('success', 'Quantity added successfully.');
        }else{
            return redirect()
                ->route('transaction.user_item', ['id' => $request->prod_sku])
                ->with('success', 'Quantity added successfully.');
        }
    }

    public function user_item($id)
    {
        $user = Auth::user();
        $transactionArea = Transaction::where('prod_sku', $id)
            ->where('location_id', $user->location->id)   
            ->whereNotNull('area_id')
            ->limit(1)
            ->oldest()
            ->get();

        $product = Product::where('prod_sku', $id)->first();
        $transfer_local = Location::where('id', '!=', $user->location->id)->get();
        $current_location = Location::find($user->location->id);
        $transactions = Transaction::oldest()
            ->whereProd_sku($id)
            ->whereLocation_id($user->location_id)
            ->get();

        $totals = [];
        $locations = Location::all();
        foreach ($locations as $location) {
            $transactionAdd = Transaction::whereLocation_id($location->id)
                ->where('prod_sku', $product->prod_sku)
                ->whereIn('tran_action',  [0, 2])
                ->sum('tran_quantity');
            $transactionRemove = Transaction::whereLocation_id($location->id)
                ->where('prod_sku', $product->prod_sku)
                ->whereIn('tran_action',  [1, 3, 4, 5])
                ->sum('tran_quantity');
            $transferAdd = Pending::where('tran_from', $location->id)
                ->where('prod_sku', $product->prod_sku)
                ->sum('tran_quantity');
            $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
            $total = $total_stock <= 0 ? 0 : $total_stock;
            $totals[$location->id] = $total;
        }

        $status = 4; // pending
        $pending = Pending::where('tran_action', $status)
            ->where('prod_sku', $product)
            ->orwhere('tran_from', $user->location_id)
            ->orWhere('location_id', $user->location_id)
            ->get();
        return view('transaction.item', 
        compact(
            'product', 
            'user', 
            'totals', 
            'locations', 
            'transactions', 
            'transfer_local', 
            'current_location',
            'pending',
            'transactionArea'
        ));  
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'tran_quantity' => 'required|numeric',
        ]);
        
        $location = Location::find($request->current_location);
        $stockIn = Transaction::whereLocation_id($location->id)
            ->whereProd_sku($request->prod_sku)
            ->whereIn('tran_action', [0, 2])
            ->sum('tran_quantity');
        $stockOut =  Transaction::whereLocation_id($location->id)
            ->whereProd_sku($request->prod_sku)
            ->whereIn('tran_action', [1, 3, 4, 5])
            ->sum('tran_quantity');
        $pending = Pending::whereLocation_id($location->id)
            ->whereProd_sku($request->prod_sku)
            ->sum('tran_quantity');
        $totalStock = $stockIn - $stockOut - $pending;
        if ($request->tran_quantity <= $totalStock) {
            $transfer = new Pending;
            $transfer->prod_sku = $request->prod_sku;
            $transfer->tran_date = $request->tran_date;
            $transfer->tran_quantity = $request->tran_quantity;
            $transfer->tran_action = 4;
            $transfer->user_id = Auth::user()->id;
            $transfer->tran_from = $request->current_location;
            $transfer->location_id = $request->loc_id;
            $transfer->tran_drno = $request->tran_drno;
            $transfer->tran_mpr = $request->tran_mpr;
            $transfer->tran_remarks = $request->tran_remarks;
            $transfer->tran_serial = $request->tran_serial;
            $transfer->save();
        
            return redirect()->back()->with('success', 'Transfer created successfully.');
        } else {
            return redirect()->back()->with('error', 'Sorry, but we dont have enough stock.');
        }
    }

    //receive transfer  
    public function transfer_item($id)
    {
        try {
            $tranfer_item = Pending::find($id);        
            $transferInAction = 2; // transfer - In
            $transferOutAction = 3; // transfer - out
            $today = date('Y-m-d');
            $transferIn = Transaction::updateOrCreate([
                'prod_sku' => $tranfer_item->prod_sku,
                'tran_date' => $today,
                'tran_quantity' => $tranfer_item->tran_quantity,
                'tran_drno' => $tranfer_item->tran_drno,
                'tran_mpr' => $tranfer_item->tran_mpr,
                'tran_serial' => $tranfer_item->tran_serial,
                'tran_remarks' => $tranfer_item->tran_remarks,
                'tran_action' => $transferInAction,
                'location_id' =>  $tranfer_item->location_id,
                'user_id' => auth()->user()->id
            ]);
            $transferOut = Transaction::updateOrCreate([
                'prod_sku' => $tranfer_item->prod_sku,
                'tran_date' => $today,
                'tran_quantity' => $tranfer_item->tran_quantity,
                'tran_drno' => $tranfer_item->tran_drno,
                'tran_mpr' => $tranfer_item->tran_mpr,
                'tran_serial' => $tranfer_item->tran_serial,
                'tran_remarks' => $tranfer_item->tran_remarks,
                'tran_action' => $transferOutAction,
                'location_id' =>  $tranfer_item->tran_from,
                'user_id' => $tranfer_item->user_id
            ]);
            $tranfer_item->delete();
            return redirect()->back()
            ->with('success', 'Transfer added successfully.');
        } catch (\Exception $e) {
            // Handle exceptions, log errors, and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    // admin
    public function edit($id, $loc_id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $location = Location::find($loc_id);
        $location_name = Location::all();
        $transactionAdd = Transaction::where('location_id', $loc_id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [0, 2])
            ->sum('tran_quantity');
        $transactionRemove = Transaction::where('location_id', $loc_id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [1, 3, 4, 5])
            ->sum('tran_quantity');
        $transferAdd = Pending::where('tran_from', $loc_id)
            ->where('prod_sku', $product->prod_sku)
            ->sum('tran_quantity');
        $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
        $total = $total_stock <= 0 ? 0 : $total_stock;
        $transactionArea = Transaction::where('prod_sku', $id)
            ->where('location_id', $loc_id)   
            ->whereNotNull('area_id')
            ->limit(1)
            ->oldest()
            ->get();
        return view('transaction.edit', 
        compact('product', 'user', 'total', 'location' , 'location_name', 'loc_id', 'transactionArea'));    
    }

    public function item($id, $loc_id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $transactions = Transaction::where('prod_sku', $id)->get();

        //for transfer form
        $transfer_local = Location::where('id', '!=', $loc_id)->get();
        $current_location = Location::find($loc_id);

        $locations = Location::all();
        $totals = [];
        foreach ($locations as $location) {
            $transactionAdd = Transaction::where('location_id', $location->id)
                ->where('prod_sku', $product->prod_sku)
                ->whereIn('tran_action',  [0, 2])
                ->sum('tran_quantity');
            
            $transactionRemove = Transaction::where('location_id', $location->id)
                ->where('prod_sku', $product->prod_sku)
                ->whereIn('tran_action',  [1, 3, 4, 5])
                ->sum('tran_quantity');
            $transferAdd = Pending::where('tran_from', $location->id)
            ->where('prod_sku', $product->prod_sku)
            ->sum('tran_quantity');
            $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
            $total = $total_stock <= 0 ? 0 : $total_stock;
            
            // Store the total for this location in the $totals array
            $totals[$location->id] = $total;
        }
        $status = 4; // pending
        $pending = Pending::whereTran_action($status)
            ->whereProd_sku($id)
            ->orwhere('tran_from', $loc_id)
            ->orWhere('location_id', $loc_id)
            ->get();  
        return view('transaction.item', 
        compact('product', 'totals', 'transactions', 'transfer_local', 'current_location', 'loc_id', 'user', 'pending'));
    }

    public function update(Request $request, $id)
    {   
        $request->validate([
            'tran_quantity' => 'required|numeric'
        ]);       
        $updateTransaction = Transaction::find($id);
        
        if (!$updateTransaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }
        $updateTransaction->tran_date = $request->tran_date;
        $updateTransaction->tran_drno = $request->tran_drno;
        $updateTransaction->tran_mpr = $request->tran_mpr;
        $updateTransaction->tran_quantity = $request->tran_quantity;
        $updateTransaction->tran_serial = $request->tran_serial;
        $updateTransaction->tran_remarks = $request->tran_remarks;
        $updateTransaction->save();
        
        return redirect()->back()->with('success', 'Transaction updated successfully.');
    }

    public function transfer_update(Request $request, $id)
    {   
        $request->validate([
            'transfer_quantity' => 'required|numeric'
        ]);       
        $updateTransfer = Pending::find($id);
        
        if (!$updateTransfer) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }
        $updateTransfer->tran_date = $request->transfer_date;
        $updateTransfer->location_id = $request->loc_id;
        $updateTransfer->tran_quantity = $request->transfer_quantity;
        $updateTransfer->tran_drno = $request->transfer_drno;
        $updateTransfer->tran_mpr = $request->transfer_mpr;
        $updateTransfer->tran_serial = $request->transfer_serial;
        $updateTransfer->tran_comment = $request->transfer_comment;
        $updateTransfer->save();
        
        return redirect()->back()->with('success', 'Transfer updated successfully.');
    }

    public function destroy($id)
    {
        $destroy = Transaction::find($id);
        $destroy->delete();
    }

    public function tranfer_destroy($id)
    {
        $destroy = Pending::find($id);
        $destroy->delete();
    }

    ///////////////////////////////              Stock Out                 ////////////////////////////////////////////////////////////////////////
    //  USER
    public function remove_show($id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $transactionAdd = Transaction::where('location_id', $user->location->id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [0, 2])
            ->sum('tran_quantity');
        $transactionRemove = Transaction::where('location_id', $user->location->id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [1, 3, 4, 5])
            ->sum('tran_quantity');
        $transferAdd = Pending::where('tran_from', $user->location->id)
            ->where('prod_sku', $product->prod_sku)
            ->sum('tran_quantity');
        $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
        $total = $total_stock <= 0 ? 0 : $total_stock;
        $transactionArea = Transaction::where('prod_sku', $id)
            ->where('location_id', $user->location->id)   
            ->whereNotNull('area_id')
            ->limit(1)
            ->oldest()
            ->get();
        $equipment = Equipment::all();
        return view('transaction.remove_edit', compact('product', 'user', 'total', 'transactionArea', 'equipment'));  
    }

    // ADMIN
    public function remove_edit($id, $loc_id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $location = Location::find($loc_id);
        $location_name = Location::all();
        $transactionAdd = Transaction::where('location_id', $loc_id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [0, 2])
            ->sum('tran_quantity');
        $transactionRemove = Transaction::where('location_id', $loc_id)
            ->where('prod_sku', $product->prod_sku)
            ->whereIn('tran_action',  [1, 3, 4, 5])
            ->sum('tran_quantity');
        $transferAdd = Pending::where('tran_from', $loc_id)
            ->where('prod_sku', $product->prod_sku)
            ->sum('tran_quantity');
        $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
        $total = $total_stock <= 0 ? 0 : $total_stock;
        $transactionArea = Transaction::where('prod_sku', $loc_id)
            ->where('location_id', $loc_id)   
            ->whereNotNull('area_id')
            ->limit(1)
            ->oldest()
            ->get();
        $equipment = Equipment::all();
        return view('transaction.remove_edit', 
        compact('product', 'user', 'total', 'location' , 'location_name', 'loc_id', 'transactionArea', 'equipment'));    
    }

    public function remove_store(Request $request){
        $request->validate([
            'tran_quantity' => 'required|numeric'
        ]);

        if ($request->tran_quantity > $request->total) {
            return redirect()->back()->with('error', 'The order quantity is higher than the current stock.');
        } else {
            $transaction = Transaction::updateOrCreate([
                'prod_sku' => $request->prod_sku,
                'tran_date' => $request->tran_date,
                'tran_quantity' => $request->tran_quantity,
                'tran_drno' => $request->tran_drno,
                'equipment_id' => $request->equipmentNo,
                'tran_serial' => $request->tran_serial,
                'tran_remarks' => $request->tran_remarks,
                'tran_action' => $request->tran_action,
                'location_id' => $request->location_id,
                'user_id' => Auth::user()->id
            ]);
            $transaction->save();
            if(session('role') == 0){
                return redirect()
                    ->route('transaction.item', ['id' => $request->prod_sku, 'loc_id' => $request->location_id])
                    ->with('success', 'Quantity removed successfully.');
            }else{
                return redirect()
                    ->route('transaction.user_item', ['id' => $request->prod_sku])
                    ->with('success', 'Quantity removed successfully.');
            }
        }

    }
}
