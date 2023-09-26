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
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $areas = Area::all();
        $manufacturers = Manufacturer::all();
        if(session('role') == 0 ){
            $products = DB::table('products')
            ->select(
                'products.prod_sku as prod_sku',
                'products.prod_name as prod_name',
                'products.prod_partno as prod_partno',
                'manufacturers.manufacturer_name as manufacturer_name',
                'areas.area_name as area_name',
                'locations.loc_name as loc_name',
                'preferences.pref_value as pref_value',
                DB::raw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) - SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_stock')
            )
            ->crossJoin('locations') // Cross join to get all locations
            ->leftJoin('transactions', function ($join) {
                $join->on('products.prod_sku', '=', 'transactions.prod_sku')
                    ->on('locations.id', '=', 'transactions.location_id');
            })
            ->leftJoin('areas', 'transactions.area_id', '=', 'areas.id')
            ->leftJoin('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
            ->leftJoin('preferences', 'preferences.id', '=', 'products.pref_id')
            ->groupBy('products.prod_sku', 'locations.loc_name')
            ->paginate();
            return view('dashboard.index', compact('products', 'areas', 'manufacturers'));
        }else{
            $user = Auth::user()->location_id;
            $location = Location::find($user);
            $products = DB::table('products')
            ->select(
                'products.prod_sku as prod_sku',
                'products.prod_name as prod_name',
                'products.prod_partno as prod_partno',
                'manufacturers.manufacturer_name as manufacturer_name',
                'areas.area_name as area_name',
                'locations.loc_name as loc_name',
                'preferences.pref_value as pref_value',
                DB::raw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) - SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_stock')
            )
            ->crossJoin('locations') // Cross join to get all locations
            ->leftJoin('transactions', function ($join) {
                $join->on('products.prod_sku', '=', 'transactions.prod_sku')
                    ->on('locations.id', '=', 'transactions.location_id');
            })
            ->leftJoin('areas', 'transactions.area_id', '=', 'areas.id')
            ->leftJoin('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
            ->leftJoin('preferences', 'preferences.id', '=', 'products.pref_id')    
            ->groupBy('products.prod_sku', 'locations.loc_name')
            ->where('location_id', $user)
            ->paginate();
            return view('dashboard.index', compact('products', 'areas', 'manufacturers', 'location'));
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
