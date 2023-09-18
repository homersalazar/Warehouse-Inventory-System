@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 sm:px-10 w-full mb-10">
        <div class="block max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow px-5">
            @if (session('role') != 1)
                <div class="sm:ml-20 mb-5">
                    <p>Note: Change location before search if you want to check other site.</p>
                </div>
            @endif
            <div class="flex flex-col sm:flex-row sm:gap-5 sm:ml-20">
                <h1 class="mt-2">Find</h1>
                <input type="text" name="product_name" id="product_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
            </div>
        </div>
        <div class="relative mt-5">
            <div id="productList"></div>
        </div>
        {{ csrf_field() }}  
    </div>
    <script>
        $(document).mouseup(function(e){
            var product = $("#productList");
            if (!product.is(e.target) && product.has(e.target).length === 0){
                product.hide();
            }
        });
        var role = {{ json_encode(session('role')) }};

        if(role == 0){
            $(document).ready(function(){
                $('#product_name').keyup(function(){
                    var query = $(this).val();
                    if(query != ''){
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url:"{{ route('product.remove_autocomplete') }}",
                            method:"POST",
                            data:{
                                query:query ,
                                loc_id, loc_id, 
                                _token:_token},
                            success:function(data){
                                $('#productList').fadeIn();
                                $('#productList').html(data);
                            }
                        });
                    }
                });
            });
        }
        
        //user
        $(document).ready(function(){
            $('#product_name').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('product.remove_autocomplete') }}",
                        method:"POST",
                        data:{
                            query:query ,
                            _token:_token},
                        success:function(data){
                            $('#productList').fadeIn();
                            $('#productList').html(data);
                        }
                    });
                }
            });
        });
    </script>
@endsection