<!-- Modal for adding new purchase order -->
<div id="addPurchaseOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold">Add New Purchase Order</h3>
            <button id="closeAddPurchaseOrderModal" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div class="mt-2 text-center text-gray-800">
            <form action="{{ route('purchase-orders.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="po_number" class="block text-sm font-medium text-gray-700 text-left">PO Number</label>
                    <input type="text" name="po_number" id="po_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="mb-4">
                    <label for="supplier_name" class="block text-sm font-medium text-gray-700 text-left">Supplier Name</label>
                    <input type="text" name="supplier_name" id="supplier_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="mb-4">
                    <label for="item_count" class="block text-sm font-medium text-gray-700 text-left">Item Count</label>
                    <input type="number" name="item_count" id="item_count" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="mb-4">
                    <label for="total" class="block text-sm font-medium text-gray-700 text-left">Total Amount</label>
                    <input type="number" step="0.01" name="total" id="total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 text-left">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="processing">Processing</option>
                        <option value="in_transit">In Transit</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="expected_delivery" class="block text-sm font-medium text-gray-700 text-left">Expected Delivery Date</label>
                    <input type="date" name="expected_delivery" id="expected_delivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">
                        Add Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newPurchaseOrderButton = document.getElementById('newPurchaseOrderButton');
        const addPurchaseOrderModal = document.getElementById('addPurchaseOrderModal');
        const closeAddPurchaseOrderModal = document.getElementById('closeAddPurchaseOrderModal');
        const cancelAddPurchaseOrder = document.getElementById('cancelAddPurchaseOrder');

        if (newPurchaseOrderButton) {
            newPurchaseOrderButton.addEventListener('click', function() {
                addPurchaseOrderModal.classList.remove('hidden');
            });
        }

        if (closeAddPurchaseOrderModal) {
            closeAddPurchaseOrderModal.addEventListener('click', function() {
                addPurchaseOrderModal.classList.add('hidden');
            });
        }

        if (cancelAddPurchaseOrder) {
            cancelAddPurchaseOrder.addEventListener('click', function() {
                addPurchaseOrderModal.classList.add('hidden');
            });
        }

        // Close modal when clicking outside of it
        if (addPurchaseOrderModal) {
            addPurchaseOrderModal.addEventListener('click', function(event) {
                if (event.target === addPurchaseOrderModal) {
                    addPurchaseOrderModal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush