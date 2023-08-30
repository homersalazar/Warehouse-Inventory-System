<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function edit($id){
        $product = Product::find($id);
        $user = Auth::user();
        if($user->role == 1){
            $transactionAdd = Transaction::where('location_id', $user->location->id)
            ->where('product_id', $product->prod_sku)
            ->where('tran_action', 0)
            ->sum('tran_quantity');
            $transactionRemove = Transaction::where('location_id', $user->location->id)
            ->where('product_id', $product->product_sku)
            ->where('tran_action', 1)
            ->sum('tran_quantity');
            $total_stock = $transactionAdd - $transactionRemove;
            $total = $total_stock <= 0 ? 0 : $total_stock;
            return view('transaction.edit', compact('product', 'user', 'total'));  
        }else{
            $product = Product::find($id);
            $user = Auth::user();
            return view('transaction.edit', compact('product', 'user'));  
        }
    }

    public function store(Request $request){
        $request->validate([
            'tran_quantity' => 'required'
        ]);
        $transaction = Transaction::firstOrCreate([
            'product_id' => $request->product_ids,
            'tran_date' => $request->tran_date,
            'tran_option' => $request->tran_option,
            'tran_quantity' => $request->tran_quantity,
            'tran_unit' => $request->tran_unit,
            'tran_serial' => $request->tran_serial,
            'tran_comment' => $request->tran_comment,
            'tran_action' => 0,
            'location_id' => $request->location_id,
            'user_id' => auth()->user()->id
        ]);
        $transaction->save();
        return redirect()->back()
        ->with('success', 'Product added successfully.');
    }
}
