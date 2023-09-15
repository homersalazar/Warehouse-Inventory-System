<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Pending;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    // {   
    //     $products = Product::all();
    //     $user = Auth::user();
    //     $productTotals = [];
        
    //     foreach ($products as $product) {
    //         $transactionAdd = Transaction::where('location_id', $user->location_id)
    //             ->where('product_id', $product->prod_sku)
    //             ->whereIn('tran_action', [0, 2])
    //             ->sum('tran_quantity');
            
    //         $transactionRemove = Transaction::where('location_id', $user->location_id)
    //             ->where('product_id', $product->prod_sku)
    //             ->whereIn('tran_action', [1, 3, 4, 5])
    //             ->sum('tran_quantity');
            
    //         $transferAdd = Pending::where('tran_from', $user->location_id)
    //             ->where('product_id', $product->prod_sku)
    //             ->sum('tran_quantity');
            
    //         $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
    //         $total = $total_stock <= 0 ? 0 : $total_stock;
        
    //         $productTotals[$product->id] = $total; // Assuming each product has a unique identifier like 'id'
    //     }

    //     $areas = Area::all();
    //     $manufacturers = Manufacturer::all();
    //     $locations = Location::find($user->location_id);
    //     return view('dashboard.index', compact('products', 'areas', 'manufacturers', 'user', 'locations', 'productTotals'));
    // }
    {
        return view('dashboard.index');
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
