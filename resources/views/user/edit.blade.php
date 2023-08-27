@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-5 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Editing {{ ucwords($user->name) }}</h1>
        <p class="mt-5 border-b-2 text-xl sm:text-2xl">Personal Data</p>
        <div class="sm:ml-16 mt-5">
            <form action="{{ route('user.update', ['id' => $user->id]) }}" method="POST" id="user_form" class="flex flex-col gap-5">
                @csrf
                @method('PATCH')
                <div class="flex flex-row gap-5">
                    <label class="font-medium mt-2">Name</label>
                    <input type="text" name="user_name" value="{{ ucwords($user->name) }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="Full Name" required>
                </div>
                <div class="flex flex-row gap-5">
                    <label class="font-medium mt-2">Email</label>
                    <input type="text" name="user_email" value="{{ $user->email }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" placeholder="Full Name" required>
                </div>
                <div class="flex flex-row gap-5">
                    <label class="font-medium mt-2">Company Admin</label>
                    <p class="mt-2">{{ $user->role == 0 ? 'Yes' : 'No' }}</p>
                </div>
            </form>
        </div>
        <p class="mt-5 border-b-2 text-xl sm:text-2xl">Role</p>
        <div class="sm:ml-16 mt-5">
            <fieldset>
                <legend class="sr-only">Role</legend>
                <div class="flex flex-col mb-4">
                    <div class="flex flex-row">
                        <input type="radio" name="user_role" form="user_form" value="1" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600" checked>
                        <label class="block ml-2 text-sm font-medium">
                            Basic User
                        </label>
                    </div>
                    <div class="flex flex-col">
                        <p class="sm:ml-6">Enter transactions at the specified locations.</p>
                        @foreach ($locations as $location)
                            <div class="flex items-center sm:ml-16 mt-4">
                                <input type="checkbox" form="user_form" name="loc_id[]" value="{{ $location->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="ml-2 text-sm font-medium">{{ $location->loc_name }}</label>
                            </div>
                        @endforeach                     
                    </div>
                </div>

                <div class="flex items-center mt-5 ">
                    <input type="radio" name="user_role" value="2" form="user_form" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600">
                    <label class="block ml-2 text-sm font-medium">
                        Reporting Only
                    </label>
                </div>
                <div class="flex flex-col">
                    <p class="sm:ml-6">View items, transactions and reports for all locations.</p>
                </div>
            </fieldset>
        </div>
        <div class="flex flex-row mt-5 sm:ml-16">
            <button type="submit" form="user_form" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
            <a href="/user" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</a>
        </div>
    </div> 
@endsection
