@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-5 sm:px-10 w-full mb-10">
        <div class="block max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow">
            <div class="flex flex-col sm:flex-row sm:gap-5 sm:ml-20">
                <h1 class="mt-2">Find</h1>
                <input type="text" name="product_name" id="product_name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                <span class="mt-2">or</span>
                <a href="{{ route('product.add_new_inventory') }}" class="mt-2 text-blue-600 underline">Add New Inventory</a>
            </div>
        </div>
        <div class="relative mt-5">
            <div id="productList"></div>
        </div>
        {{ csrf_field() }}  
    </div>
    <script>
        $(document).mouseup(function(e){
            var container = $("#productList");
            if (!container.is(e.target) && container.has(e.target).length === 0){
                container.hide();
                $("#product_name").val('');
            }
        });

        $(document).ready(function(){
            $('#product_name').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('product.product_autocomplete') }}",
                        method:"POST",
                        data:{query:query , _token:_token},
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

