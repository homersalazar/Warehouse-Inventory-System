@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Manage Areas</h1>
        <div class="relative mt-3">
            <table id="activatedTable" style="width: 100%" class="w-full text-sm text-left">
                <thead>
                    <tr>
                        <th class="py-5 px-2 pt-5">
                            <a href="{{ route('area.create') }}" class="bg-green-600 text-white px-3 rounded-md py-2">New Area</a>
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activated_area as $area)
                        <tr class="border-b">
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $area->area_name }}
                            </td>
                            <td class="px-6">
                                <a href="{{ route('area.edit', ['id' => $area->id]) }}" class="rounded-lg text-base px-2 py-2.5">
                                    <i class="fa-regular text-blue-600 fa-pen-to-square"></i>
                                </a>
                                <button type="button" onclick="deactivate_area('{{ $area->id }}', '{{ $area->area_name }}')" class="rounded-lg text-base px-2">
                                    <i class="fa-regular text-red-600 fa-circle-xmark"></i>                                
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex flex-row gap-2 mt-5">
            <a href="{{ route('area.create') }}" class="bg-green-600 text-white px-3 rounded-md py-2">New Area</a>
            <button type="button" id="show" class="undeline text-blue-600 font-semibold">Show Inactive Areas({{ $deactivated_count }})</button>
            <button type="button" id="hide" class="undeline text-blue-600 hidden font-semibold">Hide Inactive Areas</button>
        </div>
        <div id="inactive" class="hidden">
            <h1 class="sm:text-2xl mt-5 font-bold">Deactivated</h1>
            <div class="relative overflow-x-auto w-full">
                <table id="deactivatedTable" class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deactivated_area as $areas)
                            <tr class="border-b">
                                <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $areas->area_name }}
                                </td>
                                <td class="px-6 flex justify-end">
                                    <a href="{{ route('area.edit', ['id' => $areas->id]) }}" class="rounded-lg text-base px-2">
                                        <i class="fa-regular text-blue-600 fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" onclick="reactivate_area('{{ $areas->id }}' , '{{ $areas->area_name }}')" class="rounded-lg text-base px-2">
                                        <i class="fa-regular text-red-600 fa-circle-xmark"></i>                                
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const show_btn = document.getElementById("show");
        const hide_btn = document.getElementById("hide");
        const inactive_div = document.getElementById("inactive");

        show_btn.addEventListener("click", function() {
            hide_btn.classList.remove("hidden");
            inactive_div.classList.remove("hidden");
            show_btn.classList.add("hidden");
        });  
        hide_btn.addEventListener("click", function() {
            show_btn.classList.remove("hidden");
            inactive_div.classList.add("hidden");
            hide_btn.classList.add("hidden");
        });        
        
        
        const deactivate_area = (area_id, area_name) => {
            var proceed = confirm(`This action will disable ${area_name}.  Are you sure?`);
            if (proceed) {
                $.ajax({
                    url: `/area/deactivate/${area_id}`, 
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: area_id,
                    },
                    success: function(data) {
                        window.location.href = "/area"; 
                    },
                    error: function(xhr, status, error) {
                        console.error(error); 
                    }
                });
            }
        }

        const reactivate_area = (area_id, area_name) => {
            var proceed = confirm(`This action will re-enable ${area_name}. Are you sure??`);
            if (proceed) {
                $.ajax({
                    url: `/area/reactivate/${area_id}`,
                    type: "POST",
                    cache: false,
                    data:{
                        _token: '{{ csrf_token() }}',
                        id: area_id,
                    },
                    success:function(data){   
                        window.location.href = "/area";
                    }  
                });
            }
        }

        $(document).ready( function () {
            $('#activatedTable').DataTable({
                "lengthChange": false,                
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1] } // Disable sorting for the first and second columns
                ],
            });

            $('#deactivatedTable').DataTable({
                "lengthChange": false,                
                "info": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1] } // Disable sorting for the first and second columns
                ],
            });
        });
    </script>
    
@endsection