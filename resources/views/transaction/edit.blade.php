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
        <form method="POST" action="{{ route('transaction.store') }}">
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
                            <input checked type="radio" value="0" name="tran_option"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                onchange="handleRadio(this)">
                            <label class="ml-2 text-sm font-medium">New Stock</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" value="1" name="tran_option"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                onchange="handleRadio(this)">
                            <label class="ml-2 text-sm font-medium">Usable Return</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" value="2" name="tran_option"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                onchange="handleRadio(this)">
                            <label class="ml-2 text-sm font-medium">Unusable Return</label>
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
                    <label id="cost" class="sm:w-[10rem] sm:text-right">Unit Cost (&#8369;)</label>
                    <label id="refund" class="sm:w-[10rem] sm:text-right hidden">Unit Refund (&#8369;)</label>
                    <input type="number" name="tran_unit"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                </div>
                @if ($transactionArea->isEmpty())
                    <div class="flex flex-col">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Area</label>
                            <input type="text" name="area_name" autocomplete="off" id="area_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" onkeyup="search(this)">
                            <input type="hidden" name="area_id" id="area_id">  {{-- store id of the selected area --}}
                        </div>
                        <div id="areaList" class="z-10 sm:ml-[10.8rem]"></div>                                
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="sm:w-[10rem] sm:text-right">Area</label>
                        <input type="text" name="" value="{{ strtoupper($transactionArea->first()->area->area_name) }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5 bg-gray-200" readonly>
                    </div>
                @endif
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
                    <label class="sm:w-[10rem] sm:text-right">Remarks</label>
                    <input type="text" name="tran_remarks"
                        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                </div>
                <div class="flex flex-flex gap-3">
                    <input type="hidden" name="prod_sku" id="prod_sku" value="{{ $product->prod_sku }}">
                    @if (session('role') == 1)
                        <input type="hidden" name="location_id" value="{{ $user->location->id }}">
                    @else
                        <input type="hidden" name="location_id" value="{{ $location->id }}">
                    @endif
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
                    <a href="{{ route('product.add_inventory') }}"
                        class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        var cost = document.getElementById("cost");
        var refund = document.getElementById("refund");
        const handleRadio = (e) => {
            const radio = e.target.value;
            if (radio == "0") {
                refund.classList.add("hidden");
                cost.classList.remove("hidden");
            } else {
                refund.classList.remove("hidden");
                cost.classList.add("hidden");
            }
        }

        const search = (e) => {
            let Input = e.getAttribute("id");
            if(Input == 'area_name') {
                $('#area_id').val('');
            } 
        }

        $(document).mouseup(function(e){
            var area = $("#areaList");
            if (!area.is(e.target) && area.has(e.target).length === 0){
                area.hide();
            }
        });

               // autocomplete area
        $(document).ready(function(){
            $('#area_name').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('area.autocomplete') }}",
                        method:"POST",
                        data:{query:query , _token:_token},
                        success:function(data){
                            $('#areaList').fadeIn();
                            $('#areaList').html(data);
                        }
                    });
                }
            });
        });
        const fill_area = (area_id, area_name) => {
            $('#area_id').val(area_id);
            $('#area_name').val(area_name);
            $('#areaList').fadeOut();  
        }
    </script>
@endsection
