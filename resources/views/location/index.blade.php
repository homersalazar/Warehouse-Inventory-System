@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-5 sm:px-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Manage Locations</h1>
        <div class="relative overflow-x-auto mt-3">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Street Address
                        </th>
                        <th scope="col" class="px-6 py-3">
                            City
                        </th>
                        <th scope="col" class="px-6 py-3">
                            State
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Zip
                        </th>
                        <th scope="col" class="px-6 py-3">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="6" class="py-5 px-5">
                            <a href="{{ route('location.create') }}" class="bg-green-600 text-white px-3 rounded-md py-2">Add New Location</a>
                        </th>
                    </tr>
                    @foreach ($locations as $location)
                        <tr class="border-b">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $location->loc_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $location->loc_address }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $location->loc_city }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $location->loc_state }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $location->loc_zip }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('location.edit', ['id' => $location->id]) }}" class="rounded-lg text-base px-5 py-2.5 mr-2 mb-2">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
@endsection