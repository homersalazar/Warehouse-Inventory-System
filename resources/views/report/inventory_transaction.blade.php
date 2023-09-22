@extends('layouts.app')

@section('content')
    <div class="flex flex-col pt-5 px-2 sm:px-10 w-full mb-10">
        <h1 class="text-left text-lg sm:text-2xl font-bold mb-2">Inventory Transactions</h1>
        <div class="block max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow px-5 py-5">
            <form action="{{ route('report.search_inventory') }}" method="POST" id="search_inventory">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-5 sm:gap-5">
                    <div class="mb-6">
                        <label class="sm:text-right">Locations: </label>                    
                        <select name="location_id" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">  
                            <option value="" selected>Select Location</option>  
                            @foreach ($locations as $row)
                                <option value="{{ $row->id }}">{{ $row->loc_name }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="sm:text-right">Areas: </label>                    
                        <select name="area_id" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">  
                            <option selected>Select Area</option>  
                            @foreach ($areas as $row)
                                <option value="{{ $row->id }}">{{ $row->area_name }}</option>  
                            @endforeach
                        </select>                
                    </div>
                    <div class="mb-6">
                        <label class="sm:text-right">Start Date: </label>                    
                        <input type="date" name="start_date" value="{{ date('Y-m-01') }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">    
                    </div>
                    <div class="mb-6">
                        <label class="sm:text-right">End Date: </label>                    
                        <input type="date" name="end_date" value="{{ date('Y-m-t') }}" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">    
                    </div>
                    <div class="mb-6 mt-6">
                        <button type="submit" name="search" id="search" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="py-5">
            @if(isset($_POST['search']))
                <div class="flex flex-row gap-2 py-2 sm:ml-8">
                    <p class="font-bold">
                        Export Results To:
                    </p>
                    <button type="button" onclick="Excel('xlsx')" class="hover:underline font-bold text-blue-600">Excel</button> <span class="max-sm:hidden">|</span> <button onclick="PDF()" class="max-sm:hidden hover:underline font-bold text-blue-600" type="button">PDF</button>
                </div>
                <table id="transaction_table" style="width: 100%;" class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-center">
                            <th id="title" class="py-6 text-2xl underline" colspan="4">Inventory Transaction</th>
                        </tr>
                        <tr class="border-b">
                            <th scope="col" class="px-3">
                                Item
                            </th>
                            <th scope="col" class="px-3">
                                Area
                            </th>
                            <th scope="col" class="px-3">
                                SKU
                            </th>
                            <th scope="col" class="px-3">
                                Stock Count
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sql as $row)
                            <tr class="border-b">
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ ucwords($row->prod_name) }}
                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{-- {{ $row->area }} --}}
                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    SKU0{{ $row->tran_sku }}
                                </td>
                                <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $row->total_in - $row->total_out }}
                                </td>
                            </tr>
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
            var data = document.getElementById('transaction_table');
            var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
            XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
            XLSX.writeFile(excelFile, `${title}.${type}`);
        }

        const PDF = () => {
            html2canvas(document.getElementById('transaction_table'), {
                onrendered: function (canvas) {
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
