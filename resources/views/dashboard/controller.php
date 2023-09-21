{   
        $products = Product::all();
        $user = Auth::user();
        $areas = Area::all();
        $manufacturers = Manufacturer::all();
   
        if(session('role') == 0){
            $locations = Location::all();
            $productTotals = [];
        
            foreach ($products as $product) {
                $transactionAdd = Transaction::whereIn('tran_action', [0, 2])
                    ->sum('tran_quantity');
                
                $transactionRemove = Transaction::whereIn('tran_action', [1, 3, 4, 5])
                    ->sum('tran_quantity');
                
                $transferAdd = Pending::sum('tran_quantity');
                
                $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
                $total = $total_stock <= 0 ? 0 : $total_stock;
            
                $productTotals[$product->id] = $total; // Assuming each product has a unique identifier like 'id'
            }
            $transactionArea = Transaction::whereNotNull('area_id')
                ->limit(1)
                ->oldest()
                ->get();
            return view('dashboard.index', 
            compact('products', 'areas', 'manufacturers', 'user', 'locations', 'productTotals', 'transactionArea'));
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
                
                $total_stock = $transactionAdd - $transactionRemove - $transferAdd;
                $total = $total_stock <= 0 ? 0 : $total_stock;
            
                $productTotals[$product->id] = $total; // Assuming each product has a unique identifier like 'id'
            }
            $transactionArea = Transaction::where('location_id', $user->location->id)   
                ->whereNotNull('area_id')
                ->limit(1)
                ->oldest()
                ->get();
            return view('dashboard.index', 
            compact('products', 'areas', 'manufacturers', 'user', 'locations', 'productTotals', 'transactionArea'));
        }
    }


    {   
        $products = Product::all();
        $user = Auth::user();
        $areas = Area::all();
        $manufacturers = Manufacturer::all();
        if(session('role') == 0){
                $locations = Location::all();
                return view('dashboard.index',
                compact('products', 'areas', 'manufacturers', 'user', 'locations'));
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
                    ->where('prod_sku', $product->prod_sku)
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