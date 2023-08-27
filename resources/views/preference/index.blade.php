@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Preferences</h1>
        <div class="relative mt-3">
            <table id="preferenceTable" class="w-full text-sm text-left">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Value
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pref as $preferences)
                        <tr class="border-b">
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $preferences->pref_name }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $preferences->pref_value }}
                            </td>
                            <td class="px-6">
                                <a href="{{ route('preference.edit', $preferences->id ) }}" class="rounded-lg text-base px-2 py-2.5">
                                    <i class="fa-regular text-blue-600 fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    <script>
        $(document).ready( function () {
            $('#preferenceTable').DataTable({
                "lengthChange": false,                
                "info": false,
                "paging": false,
                "searching": false,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 1] }
                ],
            });
        });
    </script>
    
@endsection