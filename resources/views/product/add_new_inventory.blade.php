@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-5 sm:px-10 w-full mb-10">
        <p class="mt-5 border-b-2 text-xl sm:text-2xl font-bold">Add New Inventory</p>
        <form action="{{ route('product.store') }}" method="POST">
            {{ csrf_field() }}
            <div class="block max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow mt-5">
                <div class="flex flex-col gap-5 sm:ml-20">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="sm:w-[10rem] sm:text-right">Name</label>                    
                        <input type="text" name="prod_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" required>    
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="sm:w-[10rem] sm:text-right">SKU</label>                    
                        <input type="text" name="prod_sku" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" readonly placeholder="auto generated">    
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="sm:w-[10rem] sm:text-right">UPC</label>                    
                        <input type="text" name="prod_upc" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="if available">    
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="sm:w-[10rem] sm:text-right">Summary</label>                    
                        <input type="text" name="prod_summary" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">    
                    </div>
                    <div class="flex flex-col">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Label</label>
                            <input type="text" name="label_name" autocomplete="off" id="label_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" onkeyup="search(this)">
                            <input type="hidden" name="label_id" id="label_id">  {{-- store id of the selected label --}}
                        </div>
                        <div id="labelList" class="z-10 sm:ml-[10.8rem]"></div>                                
                    </div>
                    <div class="flex flex-col">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Area</label>
                            <input type="text" name="area_name" autocomplete="off" id="area_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" onkeyup="search(this)">
                            <input type="hidden" name="area_id" id="area_id">  {{-- store id of the selected area --}}
                        </div>
                        <div id="areaList" class="z-10 sm:ml-[10.8rem]"></div>                                
                    </div>
                    <div class="flex flex-col">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Manufacturer</label>
                            <input type="text" name="manufacturer_name" autocomplete="off" id="manufacturer_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" onkeyup="search(this)">
                            <input type="hidden" name="manufacturer_id" id="manufacturer_id">  {{-- store id of the selected area --}}
                        </div>
                        <div id="manufacturerList" class="z-10 sm:ml-[10.8rem]"></div>                                
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="sm:w-[10rem]">Minimum Stock Count</label>                    
                        <select type="text" name="pref_id" id="minStock" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                            <option value="0">Use Company Setting</option>
                            <option value="1">Override Company Setting</option>
                            <option value="2">Disable</option>
                        </select>
                        <input type="text" name="overrideInput" id="overrideInput" class="hidden border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-16 p-2.5">       
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-5 mt-2 sm:ml-20 p-6">
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Transaction Date</label>                    
                    <input type="date" name="tran_date" value="{{ date("Y-m-d") }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" required>    
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Transaction</label>                    
                    <div class="grid grid-col-1 gap-3 mt-1">
                        <div class="flex items-center">
                            <input checked type="radio" value="0" name="tran_option" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" onchange="handleRadio(this)">
                            <label class="ml-2 text-sm font-medium">New Stock</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" value="1" name="tran_option" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" onchange="handleRadio(this)">
                            <label class="ml-2 text-sm font-medium">Usable Return</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" value="2" name="tran_option" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" onchange="handleRadio(this)">
                            <label class="ml-2 text-sm font-medium">Unusable Return</label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Quantity</label>                    
                    <input type="number" name="tran_quantity" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="if available">    
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label id="cost" class="sm:w-[10rem] sm:text-right">Unit Cost (&#8369;)</label>                    
                    <label id="refund" class="sm:w-[10rem] sm:text-right hidden">Unit Refund (&#8369;)</label>                    
                    <input type="number" name="tran_unit" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">    
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Serial Number</label>                    
                    <input type="text" name="tran_serial" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">    
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <label class="sm:w-[10rem] sm:text-right">Comments</label>                    
                    <input type="text" name="tran_comment" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">    
                </div>
                <div class="flex flex-flex gap-3">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
                    <a href="{{ route('product.add_inventory') }}" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        var minStock = document.getElementById("minStock");
        var overrideInput = document.getElementById("overrideInput");
        minStock.addEventListener("change", function() {
            var selectedValue = minStock.value;
            if (selectedValue != "1") {
                overrideInput.classList.add("hidden");
            } else {
                overrideInput.classList.remove("hidden");
            }        
        });

        var cost = document.getElementById("cost");
        var refund = document.getElementById("refund");
        const handleRadio = (e) => {
            const radio = e.value;
            if(radio == "0"){
                refund.classList.add("hidden");
                cost.classList.remove("hidden");
            }else{
                refund.classList.remove("hidden");
                cost.classList.add("hidden");
            }
        }

        const search = (e) => {
            let Input = e.getAttribute("id");
            if(Input == 'area_name') {
                $('#area_id').val('');
            } else if (Input == 'manufacturer_name') {
                $('#manufacturer_id').val('');
            } else if (Input == 'label_name') {
                $('#label_id').val('');
            }
        }

        $(document).mouseup(function(e){
            var area = $("#areaList");
            var manufacturer = $("#manufacturerList");
            var label = $("#labelList");

            if (!area.is(e.target) && area.has(e.target).length === 0){
                area.hide();
            }
            if (!manufacturer.is(e.target) && manufacturer.has(e.target).length === 0){
                manufacturer.hide();
            }
            if (!label.is(e.target) && label.has(e.target).length === 0){
                label.hide();
            }
        });

        // autocomplete label
        $(document).ready(function(){
            $('#label_name').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('label.autocomplete') }}",
                        method:"POST",
                        data:{query:query , _token:_token},
                        success:function(data){
                            $('#labelList').fadeIn();
                            $('#labelList').html(data);
                        }
                    });
                }
            });
        });
        const fill_label = (label_id, label_name) => {
            $('#label_id').val(label_id);
            $('#label_name').val(label_name);
            $('#labelList').fadeOut();  
        }

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
                
        // autocomplete manufacturer
        $(document).ready(function(){
            $('#manufacturer_name').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('manufacturer.autocomplete') }}",
                        method:"POST",
                        data:{query:query , _token:_token},
                        success:function(data){
                            $('#manufacturerList').fadeIn();
                            $('#manufacturerList').html(data);
                        }
                    });
                }
            });
        });
        const fill_manufacturer = (manufacturer_id, manufacturer_name) => {
            $('#manufacturer_id').val(manufacturer_id);
            $('#manufacturer_name').val(manufacturer_name);
            $('#manufacturerList').fadeOut();  
        }

    </script>
@endsection