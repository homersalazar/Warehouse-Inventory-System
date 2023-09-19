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
    public function index(Request $request)
    {   
        $products = Product::all();
        $user = Auth::user();
        $areas = Area::all();
        $manufacturers = Manufacturer::all();
        if(session('role') == 0){
            return view('dashboard.create');
        }else if(session('role') == 1){
            $locations = Location::find($user->location_id);
            $productTotals = [];
        
            foreach ($products as $product) {
                $transactionAdd = Transaction::where('location_id', $user->location_id)
                    ->where('prod_sku', $product->prod_sku)
                    ->whereIn('tran_action', [0, 2])
                    ->sum('tran_quantity');
                
                $transactionRemove = Transaction::where('location_id', $user->location_id)
                    ->where('prod_sku', $product->prod_sku)
                    ->whereIn('tran_action', [1, 3, 4, 5])
                    ->sum('tran_quantity');
                
                $transferAdd = Pending::where('tran_from', $user->location_id)
                    ->where('prod_sku', $product->prod_sku)
                    ->sum('tran_quantity');
                
                $transactionArea = Transaction::where('location_id', $user->location_id)   
                    ->whereNotNull('area_id')
                    ->limit(1)
                    ->oldest()
                    ->get();

                $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
                $total = $total_stock <= 0 ? 0 : $total_stock;
            
                $productTotals[$product->id] = $total; 
            }
            return view('dashboard.index', 
            compact('products', 'areas', 'manufacturers', 'user', 'locations', 'productTotals', 'transactionArea'));
        }
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
