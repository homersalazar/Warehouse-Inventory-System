@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold mb-2">Daily Transactions</h1>
            <div class=" max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow px-5 py-5">
                <form action="{{ route('report.search_daily') }}" method="POST" id="search_daily">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-5 sm:gap-5">
                        <div class="mb-6">
                            <label class="sm:text-right">Start Date: </label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}"
                                class="px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="mb-6 flex-row">
                            <label class="sm:text-right">End Date: </label>
                            <div>
                                <input type="date" id="end_date" name="end_date" value="{{ date('Y-m-d') }}"
                                    class="px-5 hidden border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="py-2">
                                <input type="checkbox" id="myCheck" onclick="sameDay()" checked>
                                <label for="myCheck"> same day</label>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="sm:text-right">Locations: </label>
                            <select name="location_id"
                                class="px-5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="" selected>Select Location</option>
                                @foreach ($locations as $row)
                                    <option value="{{ $row->id }}">{{ $row->loc_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6 mt-6">
                            <button type="submit" name="search" id="search"
                                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Generate
                                Report</button>
                        </div>
                    </div>
                </form>
            </div>
        <div class="py-5">
            @if (isset($_POST['search']) && count($sql) > 0)
                <div class="flex flex-row gap-2 py-2 sm:ml-8">
                    <p class="font-bold">
                        Export Results To:
                    </p>
                    <button type="button" onclick="Excel('xlsx')"
                        class="hover:underline font-bold text-blue-600">Excel</button>
                </div>
                @php
                    $initial = '';
                    $i = 0;
                    $total = 0;
                @endphp
                <table id="daily_table" style="width: 100%;" class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-center">
                            <th id="title" class="py-6 text-2xl underline" colspan="8">Daily Transaction</th>
                        </tr>
                        @foreach ($sql as $row)
                            <tbody>
                                @php
                                    $productName = $row->prod_name;
                                    $total += $row->tran_unit;
                                    $action = $quantity = '';
                                    $quantities = $row->tran_quantity;
                                    if ($row->tran_action == 0) {
                                        $action = 'Stock - New';
                                        $quantity = $quantities;
                                    } elseif ($row->tran_action == 1) {
                                        $action = 'Stock - Out';
                                        $quantity = -$quantities;
                                    } elseif ($row->tran_action == 2) {
                                        $action = 'Transfer - In';
                                        $quantity = $quantities;
                                    } elseif ($row->tran_action == 3) {
                                        $action = 'Transfer - Out';
                                        $quantity = -$quantities;
                                    } elseif ($row->tran_action == 4) {
                                        $action = 'Return Stock';
                                        $quantity = -$quantities;
                                    } elseif ($row->tran_action == 5) {
                                        $action = 'Junk';
                                        $quantity = -$quantities;
                                    }
                                @endphp
                                @if ($productName == $initial)
                                    @php
                                        $i--;
                                    @endphp
                                    <tr class="border-b">
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_date }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->loc_name }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $action }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $quantity }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_drno }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_mpr }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_remarks }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ number_format($row->tran_unit, 2) }}
                                        </td>
                                    </tr>
                                @else
                                    @php
                                        $initial = $row->prod_name;
                                    @endphp
                                    <tr class="bg-blue-300">
                                        <td colspan="8" class="px-3 py-2 font-medium text-black whitespace-nowrap">
                                            {{ ucwords($row->prod_name) }} (SKU0{{ $row->tran_sku }})
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <th scope="col" class="px-3">
                                            Date
                                        </th>
                                        <th scope="col" class="px-3">
                                            Location
                                        </th>
                                        <th scope="col" class="px-3">
                                            Action
                                        </th>
                                        <th scope="col" class="px-3">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-3">
                                            DR No.
                                        </th>
                                        <th scope="col" class="px-3">
                                            MPR No.
                                        </th>
                                        <th scope="col" class="px-3">
                                            Remarks
                                        </th>
                                        <th scope="col" class="px-3">
                                            Unit Cost
                                        </th>
                                    </tr>
                                    </thead>
                                    <tr class="border-b">
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_date }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->loc_name }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $action }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $quantity }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_drno }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_mpr }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $row->tran_remarks }}
                                        </td>
                                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                            {{ number_format($row->tran_unit, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                @endif
                            </tbody>
                        @endforeach
                        <tfoot>
                            <tr>
                                <td colspan="8"></td>
                            </tr>
                            <tr class="bg-yellow-500">
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">

                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">

                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">

                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">

                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">

                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">

                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    Gross Overall(P):
                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ number_format($total, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                </table>
            @else
                <div class="text-center border-t border-b text-base p-2 bg-red-300">No Item found</div>
            @endif
        </div>
    </div>
    <script>
        const sameDay = () => {
            var checkBox = document.getElementById("myCheck");
            var text = document.getElementById("end_date");
            if (checkBox.checked == true) {
                text.classList.add('hidden');
            } else {
                text.classList.remove('hidden');
            }
        }

        const title = document.getElementById('title').textContent;
        const Excel = (type) => {
            var data = document.getElementById('daily_table');
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

        const PDF = () => {
            html2canvas(document.getElementById('daily_table'), {
                onrendered: function(canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download(`${title}.pdf`);
                }
            });
        }

    
    </script>
@endsection
