@extends('layouts.app')

@section('content')
@include('transaction.edit_modal')
    <div class="flex flex-col pt-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 px-5 gap-5">
            <div class="sm:ml-16">
                <h1 class="text-xl font-bold">{{ ucwords($product->prod_name) }} <span
                        class="text-lg">(SKU0{{ $product->prod_sku }})</span></h1>
            </div>
            <div class="flex flex-row sm:justify-center gap-2">
                @if (session('role') == 0)
                    <a href="/product/edit/{{ $product->prod_sku }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fa-solid fa-pen hidden md:inline-block"></i> Edit
                    </a>
                    <a href="/transaction/edit/{{ $product->prod_sku }}/loc_id/{{ $loc_id }}"
                        class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fa-solid fa-plus hidden md:inline-block"></i> Stock In
                    </a>
                    <a href="/transaction/remove_edit/{{ $product->prod_sku }}/loc_id/{{ $loc_id }}"
                        class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        <i class="fa-solid fa-minus hidden md:inline-block"></i> Stock Out
                    </a>
                @else
                    <a href="/transaction/show/{{ $product->prod_sku }}"
                        class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <i class="fa-solid fa-plus hidden md:inline-block"></i> Stock In
                    </a>
                    <a href="/transaction/remove_show/{{ $product->prod_sku }}"
                        class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        <i class="fa-solid fa-minus hidden md:inline-block"></i> Stock Out
                    </a>
                @endif
            </div>
        </div>
        <div class="block mt-5 max-w-full p-6 border border-gray-200 shadow text-white bg-gray-800 border-gray-700">
            <ul class="flex flex-col gap-5 sm:flex-row mb-5">
                @foreach ($locations as $location)
                    <li>
                        {{ $location->loc_name }} <span class="{{ $totals[$location->id] == 0 ? 'bg-red-600' : 'bg-blue-600' }} p-1 rounded-lg font-semibold">Stock: {{ $totals[$location->id] }}</span>
                    </li>
                @endforeach
            </ul>
            <div>
                <ul class="flex flex-col gap-5  mb-5">
                    @foreach ($pending as $row)
                    <li class="flex gap-1">
                        {{ $row->LocationFrom->loc_name }} transfer <span class="bg-yellow-600 p-1 rounded-lg font-semibold"> Stock:
                        {{ $row->tran_quantity }} </span> to {{ $row->LocationTo->loc_name }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <button type="button" id="transferBtn" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900 mt-5">
                <i class="fa-solid fa-right-left"></i> Transfer Inventory</button>
            <div id="transfer_form" class="hidden">
                <form action="{{ route('transaction.transfer') }}" method="post">
                    @csrf
                    <div class="flex flex-col gap-5 mt-10">
                        <h1 class="text-xl">Transfer stock from
                            {{ $current_location->loc_name }}
                        </h1>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Transaction Date</label>
                            <input type="date" name="tran_date" value="{{ date('Y-m-d') }}"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5"
                                required>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Quantity</label>
                            <input type="number" name="tran_quantity" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5" required>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">To</label>
                            <select id="loc_id" name="loc_id" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                                @foreach ($transfer_local as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->loc_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">DR No.</label>
                            <input type="text" name="tran_drno" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">MPR/PO No.</label>
                            <input type="text" name="tran_mpr" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Serial Number</label>
                            <input type="text" name="tran_serial"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="sm:w-[10rem] sm:text-right">Remarks</label>
                            <input type="text" name="tran_remarks"
                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                        </div>
                        <div class="flex flex-flex gap-3">
                            <input type="hidden" class="text-black" name="current_location" value="{{ $current_location->id }}">
                            <input type="hidden" class="text-black" name="prod_sku" value="{{ $product->prod_sku }}">
                            <button type="submit"  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
                            <button type="button" id="cancelBtn" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex flex-col gap-2 mt-5 mb-5 sm:ml-5">
                <label class="font-bold">Average Cost (₱)</label>
                0
                <label class="font-bold mt-2">Label</label>
                {{ $product->label?->label_name }}

            </div>
            <button id="moreBtn" type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><i
                    class="fa-solid fa-chevron-down"></i> More Info</button>
            <div id="infoDiv" class="flex flex-col gap-2 hidden py-5 sm:ml-5">
                <label class="font-bold">SKU</label>
                    SKU0{{ $product->prod_sku }}
                <label class="font-bold mt-2">Area</label>
                    @if (empty($transactionArea->area_id))  
                    
                    @else
                        {{ strtoupper($transactionArea->first()->area->area_name) }}
                    @endif
                <label class="font-bold mt-2">Manufacturer</label>
                    {{ ucwords($product->manufacturer?->manufacturer_name) }}
            </div>
            <button id="lessBtn" type="button"
                class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"><i
                    class="fa-solid fa-chevron-up"></i> Less Info</button>
        </div>
        <div class="px-2 sm:px-10 mb-4">
            <p class="py-5 text-xl sm:text-2xl font-bold">Transactions</p>
            @if (session('role') == 0)
                <div class="flex flex-row gap-2 py-2">
                    <label class="mt-2">Location:</label>
                    <select id="locationSelect" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-96 p-2.5">
                        <option selected>All</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->loc_name }}">{{ $loc->loc_name }}</option>
                        @endforeach
                    </select>            
                </div>
            @endif
            <table id="transactionsTable" style="width: 100%;" class="w-full text-sm text-left mt-5">
                <thead class="text-xs uppercase">
                    <tr>
                        <th scope="col" class="px-6">
                            Type
                        </th>
                        <th scope="col" class="px-6">
                            Date
                        </th>
                        <th scope="col" class="px-6">
                            Location
                        </th>
                        <th scope="col" class="px-6">
                            User
                        </th>
                        <th scope="col" class="px-6">
                            DR #
                        </th>
                        <th scope="col" class="px-6">
                            MPR/PO #
                        </th>
                        <th scope="col" class="px-6">
                            Unit Cost (₱)
                        </th>
                        <th scope="col" class="px-6">
                            Unit Sale (₱)
                        </th>
                        <th scope="col" class="px-6">
                            Quantity
                        </th>
                        <th scope="col" class="px-6">
                            Average Cost (₱)
                        </th>
                        <th scope="col" class="px-6">
                            Reason
                        </th>
                        <th scope="col" class="px-6">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $row)
                        <tr class="border-b">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                @if ($row->tran_action == 0)
                                    Add
                                @elseif(in_array($row->tran_action, [1, 3, 4, 5]))
                                    Remove
                                @else
                                    Transfer
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->tran_date }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->location->loc_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ ucwords($row->user->name) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->tran_drno }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->tran_mpr }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($row->tran_option == 0 && $row->tran_unit != '')
                                    {{ number_format($row->tran_unit, 2) }}
                                @else
                                    {{ number_format(0, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($row->tran_option == 1 && !empty($row->tran_unit))
                                    {{ number_format($row->tran_unit, 2) }}
                                @else
                                    {{ number_format(0, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($row->tran_action == 0 || $row->tran_action == 2)
                                    {{ $row->tran_quantity }}
                                @elseif (in_array($row->tran_action, [1, 3, 4, 5]))
                                    -{{ $row->tran_quantity }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ number_format($row->tran_quantity * $row->tran_unit, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($row->tran_action == 0)
                                    {{ 'Stock - New' }}
                                @elseif ($row->tran_action == 1)
                                    {{ 'Stock - Out' }}
                                @elseif ($row->tran_action == 2)
                                    {{ 'Transfer - In' }}
                                @elseif ($row->tran_action == 3)
                                    {{ 'Transfer - Out' }}
                                @elseif ($row->tran_action == 4)
                                    {{ 'Return Stock' }}
                                @elseif ($row->tran_action == 5)
                                    {{ 'Junk' }}
                                @endif
                            </td>
                            <td class="px-6">
                                <div class="flex flex-row">
                                    @if ($row->user_id == session('ID') || session('role') == 0)
                                        <button data-modal-target="edit_transaction" onclick="edit_transaction('{{ $row->id }}', '{{ $row->tran_date }}' , '{{ $row->tran_drno }}' , '{{ $row->tran_mpr }}' , '{{ $row->tran_quantity }}' , '{{ $row->tran_serial }}' , '{{ $row->tran_remarks }}')" type="button" class="{{ $row->tran_action == 2 || $row->tran_action == 3 ? 'hidden' : '' }} px-3 py-1 mr-1 text-sm font-medium text-blue-900 focus:outline-none bg-white rounded-lg border border-blue-200 hover:bg-blue-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    @endif
                                    @if (session('role') == 0)
                                        <button type="button" onclick="destroy_transaction('{{ $row->id }}')" class="{{ $row->tran_action == 2 || $row->tran_action == 3 ? 'hidden' : '' }} py-1 px-3 text-sm font-medium text-red-900 focus:outline-none bg-white rounded-lg border border-red-200 hover:bg-red-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-700">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-2 sm:px-10 mb-10">
            <p class="py-5 text-xl sm:text-2xl font-bold">Transfer Table</p>
            <table id="pendingTable" style="width: 100%;" class="w-full text-sm text-left mt-5">
                <thead class="text-xs uppercase">
                    <tr>
                        <th scope="col" class="px-6">
                            Type
                        </th>
                        <th scope="col" class="px-6">
                            Date
                        </th>
                        <th scope="col" class="px-6">
                            From
                        </th>
                        <th scope="col" class="px-6">
                            To
                        </th>
                        <th scope="col" class="px-6">
                            User
                        </th>
                        <th scope="col" class="px-6">
                            DR #
                        </th>
                        <th scope="col" class="px-6">
                            MPR/PO #
                        </th>
                        <th scope="col" class="px-6">
                            Quantity
                        </th>
                        <th scope="col" class="px-6">
                            Status
                        </th>
                        <th scope="col" class="px-6">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pending as $row)
                        <tr class="border-b">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $row->tran_action == 4 ? "Transfer" : '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->tran_date }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->locationFrom->loc_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->locationTo->loc_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->tran_drno }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->tran_mpr }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ $row->tran_quantity }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ $row->tran_action == 4 ? "Pending" : "" }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-row">
                                    @if ($row->locationFrom == $current_location || session('role') == 0)
                                        <button type="button" onclick="destroy_transfer('{{ $row->id }}')" class="py-2 px-3 mr-1 text-sm font-medium text-red-900 focus:outline-none bg-white rounded-lg border border-red-200 hover:bg-red-100 hover:text-red-700 focus:z-10 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-700">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                    @if ($row->locationTo == $current_location || session('role') == 0)
                                        <button type="submit" onclick="received_item('{{ $row->id }}' , '{{ $row->product->prod_name }}')" class="py-2 px-3 text-sm font-medium text-green-900 focus:outline-none bg-white rounded-lg border border-green-200 hover:bg-green-100 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-700">
                                            <i class="fa-solid fa-cart-arrow-down"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const transferBtn = document.getElementById('transferBtn');
        const transfer_form = document.getElementById('transfer_form');
        const cancelBtn = document.getElementById('cancelBtn');
        transferBtn.addEventListener("click", function() {
            transferBtn.classList.add("hidden");
            transfer_form.classList.remove("hidden");
        });
        cancelBtn.addEventListener("click", function() {
            transferBtn.classList.remove("hidden");
            transfer_form.classList.add("hidden");
        });

        const moreBtn = document.getElementById('moreBtn');
        const infoDiv = document.getElementById('infoDiv');
        const lessBtn = document.getElementById('lessBtn');
        moreBtn.addEventListener("click", function() {
            moreBtn.classList.add("hidden");
            infoDiv.classList.remove("hidden");
            lessBtn.classList.remove("hidden");
        });
        lessBtn.addEventListener("click", function() {
            moreBtn.classList.remove("hidden");
            infoDiv.classList.add("hidden");
            lessBtn.classList.add("hidden");
        });

        const edit_transaction = (tran_id, tran_date, tran_drno, tran_mpr, tran_quantity, tran_serial, tran_remarks) => {
            const modal = document.getElementById("edit_transaction");
            modal.style.display = "block";

            // Populate the form fields
            $("#tran_id").val(tran_id);
            $("#tran_date").val(tran_date);
            $("#tran_quantity").val(tran_quantity); 
            $("#tran_drno").val(tran_drno);
            $("#tran_mpr").val(tran_mpr);
            $("#tran_serial").val(tran_serial);
            $("#tran_remarks").val(tran_remarks);
            $("#edit_form").off("submit").on("submit", function (e) {
                e.preventDefault();
                const formData = $(this).serialize(); // Serialize the form data

                $.ajax({
                    url: `/transaction/update/${tran_id}`,
                    type: "POST", // Use POST to update the resource
                    data: formData,
                    success: function (data) {
                        // window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    },
                });
            });
        };

        const closeModal = () => {
            const modal = document.getElementById("edit_transaction");
            modal.style.display = "none";
        }
        

        const destroy_transaction = (tran_id) => {
            var proceed = confirm(`This action will proceed this delete.  Are you sure?`);
            if (proceed) {
                $.ajax({
                    url: `/transaction/delete/${tran_id}`, 
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: tran_id,
                    },
                    success: function(data) {
                        window.location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        console.error(error); 
                    }
                });
            }
        }

        const received_item = (received_id, prodName) => {
            var proceed = confirm(`This action will proceed this transfer ${prodName}.  Are you sure?`);
            if(proceed){
                $.ajax({
                    url: `/transaction/transfer_item/${received_id}`, 
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: received_id,
                    },
                    success: function(data) {
                        window.location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        console.error(error); 
                    }
                });
            }
        }

        const destroy_transfer = (tran_id) => {
            var proceed = confirm(`This action will proceed this delete.  Are you sure?`);
            if (proceed) {
                $.ajax({
                    url: `/transaction/destroy/${tran_id}`, 
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: tran_id,
                    },
                    success: function(data) {
                        window.location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        console.error(error); 
                    }
                });
            }
        }

        $(document).ready(function() {
            const table = $('#transactionsTable').DataTable({
                responsive: true,
                columnDefs: [
                    {
                        responsivePriority: 1,
                        targets: 0,
                    },
                    {
                        responsivePriority: 2,
                        targets: 11,
                    },
                ],
                lengthChange: false,
                info: false,
                // Enable sorting for the first table
                sorting: true,
            });

            $('#locationSelect').on('change', function () {
                const selectedValue = $(this).val();
                if (selectedValue === "All") {
                    table.columns(2).search('').draw();
                } else {
                    table.columns(2).search(selectedValue).draw();
                }
            });

            $('#pendingTable').DataTable({
                responsive: true,
                columnDefs: [
                    {
                        responsivePriority: 1,
                        targets: 0,
                    },
                    {
                        responsivePriority: 2,
                        targets: 9,
                    },
                ],
                lengthChange: false,
                info: false,
                searching: false,
                // Disable sorting for the second table
                sorting: false,
            });
        });

        // $(document).ready(function() {
        //     const table = $('#transactionsTable').DataTable({
        //         responsive: true,
        //         columnDefs: [
        //             {
        //                 responsivePriority: 1,
        //                 targets: 0,
        //             },
        //             {
        //                 responsivePriority: 2,
        //                 targets: 11,
        //             },
        //         ],
        //         lengthChange: false,
        //         info: false,
        //         sorting: true,
        //     });

        //     $('#locationSelect').on('change', function () {
        //         const selectedValue = $(this).val();
        //         if (selectedValue === "All") {
        //             table.columns(2).search('').draw();
        //         } else {
        //             table.columns(2).search(selectedValue).draw();
        //         }
        //     });


        //     $('#pendingTable').DataTable({
        //         responsive: true, // Enable responsive features
        //         columnDefs: [{
        //                 responsivePriority: 1,
        //                 targets: 0
        //             },
        //             {
        //                 responsivePriority: 2,
        //                 targets: 9
        //             },
        //         ],
        //         lengthChange: false,
        //         info: false,
        //         searching: false
        //         sorting: false,

        //     });
        // });
    </script>
@endsection
