<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Pending;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function item($id, $loc_id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $transactions = Transaction::where('product_id', $id)->get();

        //for transfer form
        $transfer_local = Location::where('id', '!=', $loc_id)->get();
        $current_location = Location::find($loc_id);

        $locations = Location::all();
        $totals = [];
        foreach ($locations as $location) {
            $transactionAdd = Transaction::where('location_id', $location->id)
                ->where('product_id', $product->prod_sku)
                ->whereIn('tran_action',  [0, 2])
                ->sum('tran_quantity');
            
            $transactionRemove = Transaction::where('location_id', $location->id)
                ->where('product_id', $product->prod_sku)
                ->whereIn('tran_action',  [1, 3])
                ->sum('tran_quantity');
            
            $total_stock = $transactionAdd - $transactionRemove;
            $total = $total_stock <= 0 ? 0 : $total_stock;
            
            // Store the total for this location in the $totals array
            $totals[$location->id] = $total;
        }
        $status = 4; // pending
        $pending = Pending::whereTran_action($status);     
        return view('transaction.item', compact('product', 'totals', 'transactions', 'transfer_local', 'current_location', 'loc_id', 'user', 'pending'));

    }

    public function user_item($id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $transfer_local = Location::where('id', '!=', $user->location->id)->get();
        $current_location = Location::find($user->location->id);
        $transactions = Transaction::whereProduct_id($id)
        ->whereLocation_id($user->location_id)
        ->get();

        $totals = [];
        $locations = Location::all();
        foreach ($locations as $location) {
            $transactionAdd = Transaction::where('location_id', $location->id)
                ->where('product_id', $product->prod_sku)
                ->whereIn('tran_action',  [0, 2])
                ->sum('tran_quantity');
            
            $transactionRemove = Transaction::where('location_id', $location->id)
                ->where('product_id', $product->prod_sku)
                ->whereIn('tran_action',  [1, 3])
                ->sum('tran_quantity');
            
            $total_stock = $transactionAdd - $transactionRemove;
            $total = $total_stock <= 0 ? 0 : $total_stock;
            
            // Store the total for this location in the $totals array
            $totals[$location->id] = $total;
        }

        $status = 4; // pending
        $pending = Pending::whereTran_action($status)
        ->where('tran_from', $user->location_id)
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
            'pending'
        ));  

    }

    public function edit($id, $loc_id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $location = Location::find($loc_id);
        $location_name = Location::all();
        $transactionAdd = Transaction::where('location_id', $loc_id)
        ->where('product_id', $product->prod_sku)
        ->whereIn('tran_action',  [0, 2])
        ->sum('tran_quantity');
        $transactionRemove = Transaction::where('location_id', $loc_id)
        ->where('product_id', $product->prod_sku)
        ->whereIn('tran_action',  [1, 3])
        ->sum('tran_quantity');
        $total_stock = $transactionAdd - $transactionRemove;
        $total = $total_stock <= 0 ? 0 : $total_stock;
        return view('transaction.edit', compact('product', 'user', 'total', 'location' , 'location_name', 'loc_id'));    
        
    }

    public function show($id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        $transactionAdd = Transaction::where('location_id', $user->location->id)
        ->where('product_id', $product->prod_sku)
        ->whereIn('tran_action',  [0, 2])
        ->sum('tran_quantity');
        $transactionRemove = Transaction::where('location_id', $user->location->id)
        ->where('product_id', $product->prod_sku)
        ->whereIn('tran_action',  [1, 3])
        ->sum('tran_quantity');
        $total_stock = $transactionAdd - $transactionRemove;
        $total = $total_stock <= 0 ? 0 : $total_stock;
        return view('transaction.edit', compact('product', 'user', 'total'));  
    }
    
    public function store(Request $request){
        $request->validate([
            'tran_quantity' => 'required|numeric'
        ]);
        $transaction = Transaction::updateOrCreate([
            'product_id' => $request->product_ids,
            'tran_date' => $request->tran_date,
            'tran_option' => $request->tran_option,
            'tran_quantity' => $request->tran_quantity,
            'tran_unit' => $request->tran_unit,
            'tran_drno' => $request->tran_drno,
            'tran_mpr' => $request->tran_mpr,
            'tran_serial' => $request->tran_serial,
            'tran_comment' => $request->tran_comment,
            'tran_action' => 0,
            'location_id' => $request->location_id,
            'user_id' => auth()->user()->id
        ]);
        $transaction->save();
        if(session('role') == 0){
            return redirect()
                ->route('transaction.item', ['id' => $request->product_ids, 'loc_id' => $request->location_id])
                ->with('success', 'Quantity added successfully.');
        }else{
            return redirect()
                ->route('transaction.user_item', ['id' => $request->product_ids])
                ->with('success', 'Quantity added successfully.');
        }
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'tran_quantity' => 'required|numeric',
        ]);
        $status = 4;
        $tranfer = new Pending;
        $tranfer->product_id = $request->prod_sku;
        $tranfer->tran_date = $request->tran_date;
        $tranfer->tran_quantity = $request->tran_quantity; // make a condition na bawal isubmit kapag ang tranfer quantity is greater than the current stock
        $tranfer->tran_action = $status;
        $tranfer->user_id = Auth::user()->id;
        $tranfer->tran_from = $request->current_location; // origin
        $tranfer->location_id = $request->loc_id;  // destination
        $tranfer->tran_drno = $request->tran_drno;
        $tranfer->tran_mpr = $request->tran_mpr;
        $tranfer->tran_comment = $request->tran_comment;
        $tranfer->tran_serial = $request->tran_serial;
        $tranfer->save(); 
        return redirect()->back()
        ->with('success', 'Transfer created successfully.');
    }

    public function transfer_item($id)
    {
        $transactionAction =  5;
        $tranfer_item = Pending::find($id);        
        $transferInAction = 2; // transfer - In
        $transferOutAction = 3; // transfer - out
        $today = date('Y-m-d');
        $transferIn = Transaction::updateOrCreate([
            'product_id' => $tranfer_item->product_id,
            'tran_date' => $today,
            'tran_quantity' => $tranfer_item->tran_quantity,
            'tran_drno' => $tranfer_item->tran_drno,
            'tran_mpr' => $tranfer_item->tran_mpr,
            'tran_serial' => $tranfer_item->tran_serial,
            'tran_comment' => $tranfer_item->tran_comment,
            'tran_action' => $transferInAction,
            'location_id' =>  $tranfer_item->location_id,
            'user_id' => auth()->user()->id
        ]);
        $transferOut = Transaction::updateOrCreate([
            'product_id' => $tranfer_item->product_id,
            'tran_date' => $today,
            'tran_quantity' => $tranfer_item->tran_quantity,
            'tran_drno' => $tranfer_item->tran_drno,
            'tran_mpr' => $tranfer_item->tran_mpr,
            'tran_serial' => $tranfer_item->tran_serial,
            'tran_comment' => $tranfer_item->tran_comment,
            'tran_action' => $transferOutAction,
            'location_id' =>  $tranfer_item->tran_from,
            'user_id' => $tranfer_item->user_id
        ]);
        $tranfer_item->delete();
        return redirect()->back()
        ->with('success', 'Transfer added successfully.');
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
        $updateTransaction->save();
        
        return redirect()->back()->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        $destroy = Transaction::find($id);
        $destroy->delete();
    }
}
