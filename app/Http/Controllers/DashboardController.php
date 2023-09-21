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
        $products = DB::table('transactions')
            ->select('transactions.prod_sku as tran_sku', DB::raw('MAX(products.prod_name) AS prod_name'), DB::raw('MAX(manufacturers.manufacturer_name) AS manufacturer_name') , DB::raw('MAX(areas.area_name) AS area_name') , DB::raw('MAX(locations.loc_name) AS loc_name'))
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) AS total_in')
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_out')
            ->leftJoin('products', 'products.prod_sku', '=', 'transactions.prod_sku')
            ->leftJoin('areas', 'transactions.area_id', '=', 'areas.id')
            ->leftJoin('manufacturers', 'products.manufacturer_id', '=', 'manufacturers.id')
            ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
            ->groupBy('tran_sku', 'transactions.location_id')
            ->paginate();
        return view('dashboard.index', compact('products', 'areas', 'manufacturers'));
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
