@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:items-center pt-5 px-5 sm:ml-9">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Preferences</h1>
        <form method="POST" action="{{ route('preference.update', $pref->id) }}">
            @csrf
            @method('PATCH')
            <div class="mb-6 mt-2">
                <label class="block mb-2 text-sm font-medium">Name</label>
                <input type="text"  value="{{ $pref->pref_name }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" readonly>
            </div>
            <div class="mb-6 mt-2">
                <label class="block mb-2 text-sm font-medium">Value <span class="text-red-600">*</span></label>
                <input type="number" name="pref_value" value="{{ $pref->pref_value }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" required>
            </div>
            <div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                <a href="/preference" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</a>
            </div>
        </form>
    </div>
@endsection
