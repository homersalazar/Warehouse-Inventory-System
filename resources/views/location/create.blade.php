@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-5 sm:ml-9">
        <h1 class="text-left text-lg sm:text-2xl font-bold">New Location</h1>
        <form method="POST" action="{{ route('location.store') }}">
            @csrf
            <div class="mb-6 mt-2">
                <label class="block mb-2 text-sm font-medium">Name <span class="text-red-600">*</span></label>
                <input type="text" name="loc_name"  class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="Project Site" required>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium">Street Address</label>
                <input type="text" name="loc_address" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="Street Address">
            </div>
            <div class="flex flex-row gap-4 mb-6">
                <div>
                    <label class="block mb-2 text-sm font-medium">City, State</label>
                    <input type="text" name="loc_city" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="City">
                </div>
                <div>
                    <input type="text" name="loc_state" class="mt-7 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="State">
                </div>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium">Zip</label>
                <input type="number" name="loc_zip" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="Zip">
            </div>   
            <div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                <a href="/location" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</a>
            </div>
        </form>
    </div>
@endsection
