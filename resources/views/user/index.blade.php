@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold">Manage Users</h1>
        <div class="my-5">
            <a href="" class="bg-green-600 text-white px-3 rounded-md py-2">New User</a>
        </div>
        <div class="relative">
            <table id="activatedTable" style="width: 100%;" class="w-full text-sm text-left">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Company Administrator
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Activated?
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Locations
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_activated as $user)

                        <tr class="border-b">
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ ucwords($user->name) }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->role == 0 ? "Yes" : "No" }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->status == 0 ? "Yes" : "No" }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{  $user->role == 0 ? "All Location" : $user->location->loc_name }}
                            </td>
                            <td class="px-6">
                                <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="rounded-lg text-base  py-2.5 mr-2 mb-2">
                                    <i class="fa-regular text-blue-600 fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('user.edit_password' , ['id' => $user->id]) }}" class="rounded-lg text-base  py-2.5 mr-2 mb-2">
                                    <i class="fa-solid text-green-600 fa-key"></i>
                                </a>
                                @unless($user->role == 0)
                                    <button type="button" onclick="deactivate_user('{{ $user->id }}' , '{{ $user->name }}')" class="rounded-lg text-base  py-2.5 mr-2 mb-2">
                                        <i class="fa-solid text-red-600 fa-ban"></i>
                                    </button>                                    
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex flex-row gap-2 mt-5">
            <a href="" class="bg-green-600 text-white px-3 rounded-md py-2">New User</a>
            <button type="button" id="show" class="undeline text-blue-600 font-semibold">Show Inactive Users({{ $deactivated_count }})</button>
            <button type="button" id="hide" class="undeline text-blue-600 hidden font-semibold">Hide Inactive Users</button>
        </div>
        <div id="inactive" class="hidden">
            <p class="mt-5">NOTE: Please assign a transaction locations in edit before activate the user.</p>
            <table id="deactivatedTable" style="width: 100%;" class="w-full text-sm text-left">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Company Administrator
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Activated?
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Locations
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_deactivated as $user)
                        <tr class="border-b">
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ ucwords($user->name) }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->role == 0 ? "Yes" : "No" }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->status == 0 ? "Yes" : "No" }}
                            </td>
                            <td class="px-6 font-medium text-gray-900 whitespace-nowrap">
                                {{ $user->location->loc_name }}
                            </td>
                            <td class="px-6">
                                <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="rounded-lg text-base  py-2.5 mr-2 mb-2">
                                    <i class="fa-regular text-blue-600 fa-pen-to-square"></i>
                                </a>
                                <a href="{{ route('user.edit_password' , ['id' => $user->id]) }}" class="rounded-lg text-base  py-2.5 mr-2 mb-2">
                                    <i class="fa-solid text-green-600 fa-key"></i>
                                </a>
                                @if ($user->location_id != '')
                                    <button type="button" onclick="reactivate_user('{{ $user->id }}' , '{{ $user->name }}')" class="rounded-lg text-base  py-2.5 mr-2 mb-2">
                                        <i class="fa-solid text-green-600 fa-circle-check"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

        const deactivate_user = (user_id, user_name) => {
            var proceed = confirm(`This action will disable ${user_name}.  Are you sure?`);
            if (proceed) {
                $.ajax({
                    url: `/user/deactivate/${user_id}`, 
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: user_id,
                    },
                    success: function(data) {
                        window.location.href = "/user"; 
                    },
                    error: function(xhr, status, error) {
                        console.error(error); 
                    }
                });
            }
        }

        const reactivate_user = (user_id, user_name) => {
            var proceed = confirm(`This action will re-enable ${user_name}.  Are you sure?`);
            if (proceed) {
                $.ajax({
                    url: `/user/reactivate/${user_id}`, 
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: user_id,
                    },
                    success: function(data) {
                        window.location.href = "/user"; 
                    },
                    error: function(xhr, status, error) {
                        console.error(error); 
                    }
                });
            }
        }

        $(document).ready( function () {
            $('#activatedTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 5 },
                ],
                lengthChange: false, 
                info: false,    
            });

            $('#deactivatedTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 5 },
                ],
                lengthChange: false, 
                info: false,    
            });
        });
    </script>
    
@endsection