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
                    <th scope="col" class="px-6">
                        Area
                    </th>
                    <th scope="col" class="px-6">
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
                        <select type="text" class="universal_location border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                            <option value="All Location" selected>All Location</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->loc_name }}"> {{ $location->loc_name }}</option>
                            @endforeach
                        </select>
                    </th>
                </tr>   
            </thead>
            <tbody>
                @foreach ($products as $row)
                    <tr class="border-b">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ ucwords($row->prod_name) }}
                        </td>
                        <td class="px-6 py-4">
                            SKU0{{ $row->tran_sku }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->area_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->manufacturer_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $row->loc_name }} <span>{{ $row->total_in - $row->total_out }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            const table = $('#productTable').DataTable({
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0,
                    },
                    {
                        responsivePriority: 2,
                        targets: 4,
                    },
                ],
                lengthChange: false,
                info: false,
                // searching: false,
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
            });


            // var url = window.location.href; 
            // var all_datas = url.split('?ID='); 
            // var id = (all_datas[1]); 
            // $(".universal_location").change(function(){ 
            //     $.ajax({ 
            //         type:"POST", 
            //         url:"/dashboard", 
            //         data:{id:id}, 
            //         success:function(outputs){ 

            //         } 
            //     }); 
            // }); 
            
        });
    </script>
@endsection
