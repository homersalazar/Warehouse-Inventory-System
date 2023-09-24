<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Location;
use App\Models\Pending;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function inventory_transaction()
    {
        $areas = Area::all();
        return view('report.inventory_transaction', compact('areas'));
    }

    public function search_inventory(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $areas = Area::all();
        $query = DB::table('transactions')
            ->select('transactions.prod_sku as tran_sku', DB::raw('MAX(products.prod_name) AS prod_name'))
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) AS total_in')
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_out')
            ->leftJoin('products', 'products.prod_sku', '=', 'transactions.prod_sku')
            ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
            ->groupBy('tran_sku', 'prod_name');
            $query->whereRaw('DATE(transactions.created_at) BETWEEN ? AND ?', [$start, $end]);
            if ($request->has('location_id') && $request->location_id !== null) {
                $query->where('location_id', $request->location_id);
            }
            $sql = $query->get();       
        return view('report.inventory_transaction', compact('sql', 'areas'));
    }

    public function daily_transaction()
    {
        return view('report.daily_transaction');
    }

    public function search_daily(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $locationId = $request->location_id;
        $query = DB::table('transactions')
            ->select([
                'products.prod_name',
                'products.prod_sku AS tran_sku',
                'tran_date',
                'tran_unit',
                'tran_remarks',
                'loc_name',
                'tran_action',
                'tran_quantity',
                'tran_drno',
                'tran_mpr'
            ])
            ->leftJoin('products', 'transactions.prod_sku', '=', 'products.prod_sku')
            ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
            ->whereBetween('tran_date', [$start, $end]);
            if ($request->has('location_id') && $request->location_id !== null) {
                $query->where('location_id', $request->location_id);
            }
            $sql = $query->get();
    return view('report.daily_transaction', compact('sql'));
    }

    public function new_stock()
    {
        return view('report.new_stock');
    }

    public function search_new_stock(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $locationId = $request->location_id;
        $query = DB::table('transactions')
            ->select([
                'products.prod_name',
                'products.prod_sku AS tran_sku',
                'locations.loc_name AS loc_name'
            ])
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) AS total_in')
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_out')
            ->leftJoin('products', 'transactions.prod_sku', '=', 'products.prod_sku')
            ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
            ->groupBy('transactions.location_id', 'tran_sku')
            ->whereBetween('tran_date', [$start, $end])
            ->get();
        return view('report.new_stock', compact('query'));
    }

    public function current_stock_table()
    {
        $locationSite = Location::all();
    
        $query = DB::table('products')
            ->select([
                'products.prod_name',
                'products.prod_sku AS prod_sku',
            ]);
    
        foreach ($locationSite as $loc) {
            $query->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) AND transactions.location_id = ' . $loc->id . ' THEN transactions.tran_quantity ELSE 0 END) AS total_in_' . $loc->id)
                ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) AND transactions.location_id = ' . $loc->id . ' THEN transactions.tran_quantity ELSE 0 END) AS total_out_' . $loc->id);
        }
        $query->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) AS total_in_all')
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_out_all')
            ->leftJoin('transactions', 'transactions.prod_sku', '=', 'products.prod_sku')
            ->groupBy('products.prod_name', 'products.prod_sku')
            ->orderByRaw('products.prod_name ASC'); 
        $data = $query->get();
        return view('report.current_stock_table', compact('locationSite', 'data'));
    }
    
    public function current_stock_list()
    {
        $query = DB::table('transactions')
            ->select([
                'products.prod_name',
                'products.prod_sku AS tran_sku',
                'locations.loc_name AS loc_name'
            ])
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) AS total_in')
            ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_out')
            ->leftJoin('products', 'transactions.prod_sku', '=', 'products.prod_sku')
            ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
            ->groupBy('transactions.location_id', 'tran_sku')
            ->get();
        return view('report.current_stock_list', compact('query'));    
    }

    public function low_stock_list()
    {
        $query = DB::table('transactions')
        ->select([
            'products.prod_name',
            'products.prod_sku AS tran_sku',
            'locations.loc_name AS loc_name',
            'preferences.pref_value'
        ])
        ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) AS total_in')
        ->selectRaw('SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) AS total_out')
        ->leftJoin('products', 'transactions.prod_sku', '=', 'products.prod_sku')
        ->leftJoin('preferences', 'preferences.id', '=', 'products.pref_id')
        ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
        ->groupBy('transactions.location_id', 'tran_sku')
        ->havingRaw('total_in - total_out <= preferences.pref_value')
        ->get();
        return view('report.low_stock_list', compact('query'));      
    }

    public function unavailable()
    {
        $query = DB::table('products')
        ->select([
            'products.prod_name',
            'products.prod_sku AS tran_sku',
            'locations.loc_name AS loc_name'
        ])
        ->leftJoin('transactions', 'transactions.prod_sku', '=', 'products.prod_sku')
        ->leftJoin('locations', 'transactions.location_id', '=', 'locations.id')
        ->groupBy('transactions.location_id', 'tran_sku')
        ->havingRaw('SUM(CASE WHEN transactions.tran_action IN (0, 2) THEN transactions.tran_quantity ELSE 0 END) - SUM(CASE WHEN transactions.tran_action IN (1, 3, 4, 5) THEN transactions.tran_quantity ELSE 0 END) = 0')
        ->get();
        return view('report.unavailable', compact('query'));      
    }
}
