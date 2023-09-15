@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-5 sm:px-10 w-full mb-10">
        <h1 class="text-left text-xl sm:text-2xl font-bold">Set Password for {{ ucwords($user->name) }}</h1>
        <p class="mt-5 border-b-2 text-lg sm:text-2xl font-bold">Set Password</p>
        <div class="sm:ml-16 mt-5">
            <form action="{{ route('user.update_password', ['id' => $user->id]) }}" method="POST" class="flex flex-col gap-2 sm:gap-5">
                @csrf
                @method('PATCH')
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-5">
                    <label class="font-medium mt-2">Password</label>
                    <input type="password" name="password" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" required>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-5">
                    <label class="font-medium mt-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5"  required>
                </div>
                <div class="flex flex-row mt-5">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                    <a href="/user" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div> 
@endsection
