<?php

namespace App\Http\Controllers;

use App\Models\Area;
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
}
