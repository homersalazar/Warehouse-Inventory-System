@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold mb-2">New Stock Transactions</h1>
            <div class=" max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow px-5 py-5">
                <form action="{{ route('report.search_new_stock') }}" method="POST" id="search_new_stock">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-5 sm:gap-5">
                        <div class="mb-6">
                            <label class="sm:text-right">Start Date: </label>
                            <input type="date" name="start_date" value="{{ date('Y-m-01') }}"
                                class="px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="mb-6">
                            <label class="sm:text-right">End Date: </label>
                            <input type="date" id="end_date" name="end_date" value="{{ date('Y-m-t') }}" class="px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="mb-6 mt-6">
                            <button type="submit" name="search" id="search"class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Generate Report</button>
                        </div>
                    </div>
                </form>
            </div>
        <div class="py-5">
            @if (isset($_POST['search']))
                <div class="flex flex-row gap-2 py-2 sm:ml-8">
                    <p class="font-bold">
                        Export Results To:
                    </p>
                    <button type="button" onclick="Excel('xlsx')" class="hover:underline font-bold text-blue-600">Excel</button>
                </div>
                <div class=" max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow px-5 py-5">
                    <p>Showing transactions starting {{ date('F j, Y', strtotime($_POST['start_date'])) }} through {{ date('F j, Y', strtotime($_POST['end_date'])) }} for all locations and all areas</p>
                </div>
                <table id="new_stock_table" style="width: 100%;" class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-center">
                            <th id="title" class="py-6 text-2xl underline" colspan="4">New Stock Transaction</th>
                        </tr>
                        <tr class="border-b">
                            <th scope="col" class="px-3">
                                Item(SKU)
                            </th>
                            <th scope="col" class="px-3">
                                Total Added
                            </th>
                            <th scope="col" class="px-3">
                                Total Removed
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $initial = '';
                            $i = 0;
                            $total = 0;
                            
                        @endphp
                        @forelse ($query as $row)
                            @php
                                $locationName =  $row->loc_name;
                            @endphp
                            @if ($locationName == $initial)
                                @php
                                    $i--;
                                @endphp
                                <tr class="border-b">
                                    <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ ucwords($row->prod_name) }} (sku0{{ $row->tran_sku }})
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $row->total_in }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ !empty($row->total_out) ? -$row->total_out : 0 }}
                                    </td>
                                </tr>
                            @else
                                @php
                                    $initial = $row->loc_name;
                                @endphp
                                <tr class="bg-blue-300">
                                    <td colspan="8" class="px-3 py-2 font-medium text-black whitespace-nowrap">
                                        {{ strtoupper($row->loc_name) }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ ucwords($row->prod_name) }} (sku0{{ $row->tran_sku }})
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $row->total_in }}
                                    </td>
                                    <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ !empty($row->total_out) ? -$row->total_out : 0 }}
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr class="border-t border-b text-center text-base p-2">
                                <td colspan="4" class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    No result found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <script>
        const title = document.getElementById('title').textContent;
        const Excel = (type) => {
            var data = document.getElementById('new_stock_table');
            var excelFile = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(excelFile, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(excelFile, `${title}.${type}`);
        }
    </script>
@endsection
