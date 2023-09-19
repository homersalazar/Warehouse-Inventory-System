@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Manage Unit</h1>
        <div class="mt-5">
            <a href="{{ route('unit.create') }}" class="bg-green-600 text-white px-3 rounded-md py-2">New unit</a>
        </div>
        <div class="relative mt-3">
            <table id="unitTable" class="w-full text-sm text-left">
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
                    @foreach ($units as $unit)
                        <tr class="border-b">
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $unit->unit_name }}
                            </td>
                            <td class="px-6 text-right">
                                <a href="{{ route('unit.edit', ['id' => $unit->id]) }}" class="rounded-lg text-base px-2 py-2.5">
                                    <i class="fa-regular text-blue-600 fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
  
        $(document).ready( function () {
            $('#unitTable').DataTable({
                "lengthChange": false,                
                "info": false,
                "searching": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1] } 
                ],
            });
        });
    </script>
    
@endsection