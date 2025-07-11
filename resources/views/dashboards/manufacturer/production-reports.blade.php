@extends('layouts.dashboard')

@section('title', 'Production Reports')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <h1 class="page-header-manufacturer">Production Reports</h1>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Filter Reports</h3>
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
                <input type="date" id="startDate" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
                <input type="date" id="endDate" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Production Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Total Items Processed</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalItemsProcessed }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Total Completed Items</p>
            <p class="text-3xl font-bold text-green-600">{{ $totalCompletedItems }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
            <p class="text-sm text-gray-600">Total Failed Items</p>
            <p class="text-3xl font-bold text-red-600">{{ $totalFailedItems }}</p>
        </div>
    </div>

    <!-- Detailed Process Flow Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Detailed Process Flow</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stage</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entered Stage At</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed Stage At</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Failure Reason</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($processFlows as $flow)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $flow->item_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $flow->current_stage)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $flow->status)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flow->entered_stage_at ? \Carbon\Carbon::parse($flow->entered_stage_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flow->completed_stage_at ? \Carbon\Carbon::parse($flow->completed_stage_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flow->failure_reason ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No process flow data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end space-x-2">
            <button onclick="exportReport('csv')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Export to CSV</button>
            <button onclick="exportReport('pdf')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Export to PDF</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        // Reload the page with query parameters for filtering
        window.location.href = `{{ route('manufacturer.production-reports') }}?start_date=${startDate}&end_date=${endDate}`;
    });

    function exportReport(format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        window.location.href = `{{ route('manufacturer.production-reports') }}?start_date=${startDate}&end_date=${endDate}&export=${format}`;
    }
</script>
@endpush