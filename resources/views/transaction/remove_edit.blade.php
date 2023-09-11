@extends('layouts.app')

@section('content')
    <div class="flex flex-col  pt-5 px-5 sm:ml-16">
        @if (session('role') == 0)
            <a href={{ route('transaction.item', ['id' => $product->prod_sku, 'loc_id' => $loc_id]) }}
                class="text-left text-2xl sm:text-2xl font-bold underline hover:text-blue-700">{{ ucwords($product->prod_name) }}
            </a>
        @else
            <a href={{ route('transaction.user_item', ['id' => $product->prod_sku]) }}
                class="text-left text-2xl sm:text-2xl font-bold underline hover:text-blue-700">{{ ucwords($product->prod_name) }}
            </a>
        @endif
        <p class="py-2 font-semibold">(SKU0{{ $product->prod_sku }}
            )</p>
        @if (session('role') == 1)
            <h1 class="font-bold text-xl">{{ $user->location->loc_name }} has <span>{{ $total }}</span> in-stock</h1>
        @else
            <h1 class="font-bold text-xl">{{ ucwords($location->loc_name) }} has <span>{{ $total }}</span> in-stock</h1>
        @endif
        <form method="POST" action="{{ route('transaction.remove_store') }}">
            @csrf
            <div class="flex flex-col gap-5 mt-2">
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Transaction Date</label>
                    <input type="date" name="tran_date" value="{{ date('Y-m-d') }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5"
                        required>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Transaction</label>
                    <div class="grid grid-col-1 gap-3 mt-1">
                        <div class="flex items-center">
                            <select type="text" name="pref_id" id="minStock" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                                <option value="0">Stock Out</option>
                                <option value="1">Return Stock</option>
                                <option value="2">Junk</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Quantity</label>
                    <input type="number" name="tran_quantity"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5"
                        required>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Area</label>
                    <input type="text" name="" value="{{ $product->area->area_name }}"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5 bg-gray-200" readonly>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">DR No.</label>
                    <input type="text" name="tran_drno"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">MPR/PO No.</label>
                    <input type="text" name="tran_mpr"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Serial Number</label>
                    <input type="text" name="tran_serial"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Comments</label>
                    <input type="text" name="tran_comment"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                </div>
                <div class="flex flex-flex gap-3">
                    <input type="hidden" name="product_ids" id="product_ids" value="{{ $product->prod_sku }}">
                    @if (session('role') == 1)
                        <input type="hidden" name="location_id" value="{{ $user->location->id }}">
                    @else
                        <input type="hidden" name="location_id" value="{{ $location->id }}">
                    @endif
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
                    <a href="{{ route('product.remove_inventory') }}" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
