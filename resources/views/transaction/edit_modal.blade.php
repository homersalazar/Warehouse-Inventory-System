<div id="edit_transaction" data-modal-target="edit_transaction" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center py-5 sm:py-0">
    <div class="fixed mx-auto max-w-2xl inset-0 flex items-center justify-center px-2 sm:px-0">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-full">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-white">
                    Edit Transaction
                </h3>
                <button type="button" onclick="closeModal()" class="bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit_transaction">
                    <i class="fa-solid fa-xmark text-white"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6 text-white">
                <form method="POST" id="edit_form">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col gap-2 sm:w-[40rem]">
                        <div class="flex flex-col sm:flex-row mb-2">
                            <label class="block w-full sm:w-1/4  mb-2 text-sm font-medium text-gray-900 dark:text-white">Transaction Date</label>
                            <input type="date" name="tran_date" id="tran_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>  
                        <div class="flex flex-col sm:flex-row mb-2">
                            <label class="block w-full sm:w-1/4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantity</label>
                            <input type="number" name="tran_quantity" id="tran_quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>  
                        <div class="flex flex-col sm:flex-row mb-2">
                            <label class="block w-full sm:w-1/4 mb-2 text-sm font-medium text-gray-900 dark:text-white">DR No.</label>
                            <input type="text" name="tran_drno" id="tran_drno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div> 
                        <div class="flex flex-col sm:flex-row mb-2">
                            <label class="block w-full sm:w-1/4 mb-2 text-sm font-medium text-gray-900 dark:text-white">MPR/PO No.</label>
                            <input type="text" name="tran_mpr" id="tran_mpr" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div> 
                        <div class="flex flex-col sm:flex-row mb-2">
                            <label class="block w-full sm:w-1/4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Serial No.</label>
                            <input type="text" name="tran_serial" id="tran_serial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div> 
                        <div class="flex flex-col sm:flex-row mb-2">
                            <label class="block w-full sm:w-1/4 mb-2 text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
                            <input type="text" name="tran_remarks" id="tran_remarks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div> 
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" form="edit_form" data-modal-hide="edit_transaction" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                <button onclick="closeModal()" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
            </div>
        </div>
    </div>
</div>