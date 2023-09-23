@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold mb-2">Current Stock Transactions</h1>
        <div class=" max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow px-5 py-5">
            <p>Showing transactions starting  through for all locations and all areas</p>
        </div>
        <table id="new_stock_table" style="width: 100%;" class="w-full text-sm text-left">
            <thead>
                <tr class="text-center">
                    <th id="title" class="py-6 text-2xl underline" colspan="5">Current Stock Table</th>
                </tr>
                <tr class="border-b">
                    <th scope="col" class="px-3">
                        Item
                    </th>
                    <th scope="col" class="px-3">
                        SKU
                    </th>
                    @foreach ($locationSite as $loc)
                        <th scope="col" class="px-3">
                            {{ $loc->loc_name }}
                        </th>
                    @endforeach
                    <th scope="col" class="px-3">
                        All Location
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr class="border-b">
                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ ucwords($row->prod_name) }}
                        </td>
                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                            sku0{{ $row->prod_sku }}
                        </td>
                        @foreach ($locationSite as $loc)
                            <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                {{ $row->{'total_in_' . $loc->id} - $row->{'total_out_' . $loc->id} }}
                            </td>
                        @endforeach
                        <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                            {{ $row->total_in_all - $row->total_out_all }}
                        </td>
                    </tr>
                @endforeach
            </tbody>            
        </table>
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
