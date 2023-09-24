@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold mb-2">Out of Stock List</h1>
        <div class="py-5">
            <div class="flex flex-col gap-2">
                <p>
                    An item is considered out of stock in a given location if it has ever been in-stock at that location but currently has no inventory.
                </p>
                <p>
                    An item that has never been in-stock at a given location will not be listed as out of stock.
                </p>
            </div>
            <div class="flex flex-row gap-2 py-2 sm:ml-8">
                <p class="font-bold">
                    Export Results To:
                </p>
                <button type="button" onclick="Excel('xlsx')" class="hover:underline font-bold text-blue-600">Excel</button>
            </div>
            <table id="unavailable" style="width: 100%;" class="w-full text-sm text-left">
                <thead>
                    <tr class="text-center">
                        <th id="title" class="py-6 text-2xl underline" colspan="4">Out of Stock List</th>
                    </tr>
                    <tr class="border-b">
                        <th scope="col" class="px-3">
                            Item(SKU)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $initial = '';
                        $i = 0;
                        $total = 0;
                        
                    @endphp
                    @foreach ($query as $row)
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
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const title = document.getElementById('title').textContent;
        const Excel = (type) => {
            var data = document.getElementById('unavailable');
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
