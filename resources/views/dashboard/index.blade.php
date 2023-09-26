@extends('layouts.app')

@section('content')
    <div class="grid px-5 pt-10 sm:px-10 w-full">
        <table id="productTable" style="width: 100%;" class="w-full text-sm text-left mt-5">
            <thead class="text-xs uppercase">
                <tr>
                    <th scope="col" class="px-6">
                        Name
                    </th>
                    <th scope="col" class="px-6">
                        Sku
                    </th>
                    <th scope="col" class="px-6 max-sm:hidden">
                        Area
                    </th>
                    <th scope="col" class="px-6 max-sm:hidden">
                        Manufacturers
                    </th>
                    <th scope="col" class="px-6">
                        Locations (in-stock)
                    </th>
                </tr>
                <tr class="max-sm:hidden">
                    <th class="px-6 py-4">
                        <input type="text" id="nameInput" name=""
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                            placeholder="Search by Name (any parts)">
                    </th>
                    <th class="px-6 py-4">
                        <input type="text" id="skuInput" name=""
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"
                            placeholder="Search by SKU">
                    </th>
                    <th class="px-6 py-4">
                        <select type="text" id="areaSelect" name=""
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                            <option selected>Choose Area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->area_name }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th class="px-6 py-4">
                        <select type="text" id="manufacturerSelect" name=""
                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                            <option selected>Choose Manufacturer</option>
                            @foreach ($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->manufacturer_name }}">
                                    {{ $manufacturer->manufacturer_name }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th class="px-6 py-4">
                        <select type="text" id="universal_location" class="universal_location border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                            @if (session('role') == 0)
                                <option value="All Location" selected>All Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->loc_name }}"> {{ $location->loc_name }}</option>
                                @endforeach
                            @else
                                <option value="{{ $location->loc_name }}"> {{ $location->loc_name }}</option>
                            @endif
                        </select>
                    </th>
                </tr>   
            </thead>
            <tbody>
                @php
                    $currentSku = null; // Initialize current SKU
                @endphp
                    @foreach ($products as $product)
                        @if ($product->prod_sku !== $currentSku)
                            <tr class="border-b">
                                <td class="px-6 py-4">
                                    @if (session('role') ==0)
                                        {{ ucwords($product->prod_name) }}
                                    @else 
                                        <a href="/transaction/item/{{ $product->prod_sku }}" class="underline text-blue-600" id="product_link">{{ ucwords($product->prod_name) }}</a>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    SKU0{{ $product->prod_sku }}                                                                   
                                </td>
                                <td class="px-6 py-4 max-sm:hidden">
                                    {{ $product->area_name }}
                                </td>
                                <td class="px-6 py-4 max-sm:hidden">
                                    {{ $product->manufacturer_name }}
                                </td>
                                <td class="px-6">
                                    @if ($product->pref_value <= $product->total_stock)
                                        {{ $product->loc_name }} ({{ $product->total_stock }})    
                                    @else
                                        {{ $product->loc_name }}
                                        <span class="bg-red-600 px-2.5 py-0.5 rounded-lg text-white font-bold">
                                            ({{ $product->total_stock }})                                        
                                        </span>                   
                                    @endif                                
                                </td>
                            </tr>
                        @else
                            {{-- Continue adding rows for the same SKU --}}
                            <tr class="border-b">
                                <td class="primary_hidden invisible px-6">
                                    @if (session('role') ==0)
                                        {{ ucwords($product->prod_name) }}
                                    @else 
                                        <a href="/transaction/item/{{ $product->prod_sku }}" class="underline text-blue-600" id="product_link">{{ ucwords($product->prod_name) }}</a>
                                    @endif                             
                                </td>
                                <td class="primary_hidden invisible px-6 py-4">
                                    SKU0{{ $product->prod_sku }}                                                                   
                                </td>
                                </td>
                                <td class="px-6 py-4 max-sm:hidden">
                                    {{ $product->area_name }}
                                </td>
                                <td class="primary_hidden invisible px-6 py-4 max-sm:hidden">
                                    {{ $product->manufacturer_name }}
                                </td>
                                <td class="px-6">
                                    @if ($product->pref_value <= $product->total_stock)
                                        {{ $product->loc_name }} ({{ $product->total_stock }})    
                                    @else
                                        {{ $product->loc_name }}
                                        <span class="bg-red-600 px-2.5 py-0.5 rounded-lg text-white font-bold">
                                            ({{ $product->total_stock }})                                        
                                        </span>                   
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @php
                            $currentSku = $product->prod_sku; // Update current SKU
                        @endphp
                    @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            const table = $('#productTable').DataTable({
                lengthChange: false,
                info: false,
                sort: false
            });

            // Event listener for the input element
            $('#nameInput').on('input', function() {
                const inputValue = $(this).val();
                if (inputValue === "") {
                    table.columns(0).search('').draw();
                } else {
                    table.columns(0).search(inputValue).draw();
                }
            });

            // Event listener for the input element
            $('#skuInput').on('input', function() {
                const inputValue = $(this).val();
                if (inputValue === "") {
                    table.columns(1).search('').draw();
                } else {
                    table.columns(1).search(inputValue).draw();
                }
            });

            // Event listener for the select element
            $('#areaSelect').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue === "Choose Area") {
                    table.columns(2).search('').draw();
                } else {
                    table.columns(2).search(selectedValue).draw();
                }
            });

            // Event listener for the select element
            $('#manufacturerSelect').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue === "Choose Manufacturer") {
                    table.columns(3).search('').draw();
                } else {
                    table.columns(3).search(selectedValue).draw();
                }
            });

            // Event listener for the select element
            $('.universal_location').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue === "All Location") {
                    table.columns(4).search('').draw();
                } else {
                    table.columns(4).search(selectedValue).draw();
                }

                if (selectedValue !== "All Location") {
                    $('.primary_hidden').removeClass('invisible');
                } else {
                    $('.primary_hidden').addClass('invisible');
                }
            });
        });
    </script>
@endsection
