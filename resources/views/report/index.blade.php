@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Reports</h1>
        <div class="grid grid-cols-2 sm:grid-cols-5 mt-5 gap-1">
            <a href="/report/inventory_transaction" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-cubes-stacked text-2xl sm:text-base"></i> <span class="max-sm:hidden">Inventory Transaction</span></a>
            <a href="/report/daily_transaction" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-calendar-days text-2xl sm:text-base"></i> <span class="max-sm:hidden">Daily Transaction</span></a>
            <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-list-check text-2xl sm:text-base"></i> <span class="max-sm:hidden"> New Stock Counts</span></a>
            <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-table-list text-2xl sm:text-base"></i> <span class="max-sm:hidden">Current Stock Table</span></a>
            <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-list text-2xl sm:text-base"></i> <span class="max-sm:hidden">Current Stock List</span></a>
            <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-list text-2xl sm:text-base"></i> <span class="max-sm:hidden">Low Stock List</span></a>
            <a href="" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 max-sm:text-center"><i class="fa-solid fa-list text-2xl sm:text-base"></i> <span class="max-sm:hidden">Out of Stock List</span></a>
        </div>
    </div>
@endsection