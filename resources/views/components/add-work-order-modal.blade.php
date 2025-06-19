<!-- Modal for adding new work order -->
<div id="addWorkOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold">Add New Work Order</h3>
            <button id="closeAddWorkOrderModal" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div class="mt-2 text-center text-gray-800">
            <form action="{{ route('work-orders.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="product_name" class="block text-sm font-medium text-gray-700 text-left">Product Name</label>
                    <input type="text" name="product_name" id="product_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 text-left">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 text-left">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 text-left">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 text-left">Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center space-x-2 transition duration-200">
                        Add Work Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 