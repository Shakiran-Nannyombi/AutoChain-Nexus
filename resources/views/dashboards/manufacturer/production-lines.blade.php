@extends('layouts.dashboard')

@section('title', 'Production Lines')

@section('sidebar-content')
    @include('dashboards.manufacturer.sidebar')
@endsection

@section('content')
    <h1 class="page-header-manufacturer">Production Lines</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $productionLines = [];
            foreach ($processFlows as $flow) {
                $itemName = $flow->item_name;
                if (!isset($productionLines[$itemName])) {
                    $productionLines[$itemName] = [
                        'completed_items' => 0,
                        'total_items' => 0,
                        'has_failed_item' => false,
                        'has_incomplete_item' => false,
                        'current_stage' => 'N/A', // Will be updated later
                        'status' => 'N/A', // Will be updated later
                        'max_stage_progress' => 0, // To track the furthest stage reached
                    ];
                }
                $productionLines[$itemName]['total_items']++;

                if ($flow->status === 'completed') {
                    $productionLines[$itemName]['completed_items']++;
                } else {
                    $productionLines[$itemName]['has_incomplete_item'] = true;
                }
                if ($flow->status === 'failed') {
                    $productionLines[$itemName]['has_failed_item'] = true;
                }
            }

            // Define stage order and their corresponding progress percentages
            $stageProgressMap = [
                'raw_materials' => 20,
                'manufacturing' => 40,
                'quality_control' => 60,
                'distribution' => 80,
                'retail' => 100,
                'completed' => 100, // Completed items are 100% done
                'failed' => 0, // Failed items don't contribute to forward progress
            ];

            // Determine the overall status and current stage for each line
            foreach ($productionLines as $itemName => &$data) {
                // Calculate max_stage_progress for the line
                $currentLineItems = collect($processFlows)->where('item_name', $itemName);
                foreach ($currentLineItems as $item) {
                    $stage = $item->status === 'completed' ? 'completed' : ($item->status === 'failed' ? 'failed' : $item->current_stage);
                    $progress = $stageProgressMap[$stage] ?? 0;
                    if ($progress > $data['max_stage_progress']) {
                        $data['max_stage_progress'] = $progress;
                    }
                }

                if ($data['has_failed_item']) {
                    $data['status'] = 'stopped';
                    $failedItem = $currentLineItems->where('status', 'failed')->first();
                    $data['current_stage'] = $failedItem->current_stage ?? 'N/A';
                } elseif (!$data['has_incomplete_item']) {
                    $data['status'] = 'completed';
                    $data['current_stage'] = 'Finished';
                } else {
                    $data['status'] = 'running';
                    $incompleteItem = $currentLineItems
                                        ->where('status', '!=', 'completed')
                                        ->sortBy('entered_stage_at')
                                        ->first();
                    $data['current_stage'] = $incompleteItem->current_stage ?? 'N/A';
                }
            }
            unset($data); // Unset the reference

            $totalItems = count($processFlows);
            $completedItems = collect($processFlows)->where('status', 'completed')->count();
            $failedItems = collect($processFlows)->where('status', 'failed')->count();

            // Dummy Takt Time / Cycle Time
            $avgCycleTime = 55;
            $targetCycleTime = 60;

        @endphp

        @foreach ($productionLines as $modelName => $data)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Line: {{ $modelName }}</h3>
                        @php
                            $statusClass = [
                                'running' => 'bg-green-500',
                                'stopped' => 'bg-red-500',
                                'completed' => 'bg-blue-600',
                            ][$data['status']] ?? 'bg-gray-500';
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold text-white {{ $statusClass }} rounded-full">{{ ucfirst(str_replace('_', ' ', $data['status'])) }}</span>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Current Stage: <strong>{{ $data['current_stage'] }}</strong></p>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-semibold">Production Progress</p>
                        <div class="w-full bg-gray-200 rounded-full mt-1">
                            <div class="bg-blue-500 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: {{ $data['max_stage_progress'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $data['completed_items'] }} / {{ $data['total_items'] }} items completed</p>
                    </div>
                    <div class="mt-4">
                        <button onclick="showUpdateForm('{{ $modelName }}')" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-2 rounded text-xs">Update Status</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Update Status Form (Hidden by default) -->
    <div id="updateStatusFormContainer" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Update Production Line Status</h3>
            <form id="updateStatusForm" onsubmit="event.preventDefault(); submitUpdateForm();">
                @csrf
                <input type="hidden" id="formItemName" name="item_name">

                <div class="mb-4">
                    <label for="newStage" class="block text-sm font-medium text-gray-700">Next Stage:</label>
                    <select id="newStage" name="new_stage" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="raw_materials">Raw Materials</option>
                        <option value="manufacturing">Manufacturing</option>
                        <option value="quality_control">Quality Control</option>
                        <option value="distribution">Distribution</option>
                        <option value="retail">Retail</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <div class="mb-4">
                    <input type="checkbox" id="markAsFailed" name="mark_as_failed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="markAsFailed" class="ml-2 text-sm font-medium text-gray-700">Mark as Failed</label>
                </div>

                <div id="failureReasonContainer" class="mb-4 hidden">
                    <label for="failureReason" class="block text-sm font-medium text-gray-700">Failure Reason:</label>
                    <input type="text" id="failureReason" name="failure_reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-200 focus:ring-opacity-50">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideUpdateForm()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Submit Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // --- Form and AJAX Logic ---
    const updateStatusFormContainer = document.getElementById('updateStatusFormContainer');
    const updateStatusForm = document.getElementById('updateStatusForm');
    const formItemNameInput = document.getElementById('formItemName');
    const markAsFailedCheckbox = document.getElementById('markAsFailed');
    const failureReasonContainer = document.getElementById('failureReasonContainer');
    const failureReasonInput = document.getElementById('failureReason');

    markAsFailedCheckbox.addEventListener('change', function() {
        if (this.checked) {
            failureReasonContainer.classList.remove('hidden');
            failureReasonInput.setAttribute('required', 'required');
        } else {
            failureReasonContainer.classList.add('hidden');
            failureReasonInput.removeAttribute('required');
            failureReasonInput.value = ''; // Clear value when hidden
        }
    });

    function showUpdateForm(itemName) {
        formItemNameInput.value = itemName;
        // Reset form state
        markAsFailedCheckbox.checked = false;
        failureReasonContainer.classList.add('hidden');
        failureReasonInput.removeAttribute('required');
        failureReasonInput.value = '';
        document.getElementById('newStage').value = 'raw_materials'; // Default to first stage

        updateStatusFormContainer.classList.remove('hidden');
    }

    function hideUpdateForm() {
        updateStatusFormContainer.classList.add('hidden');
    }

    async function submitUpdateForm() {
        const itemName = formItemNameInput.value;
        const newStage = document.getElementById('newStage').value;
        const markFailed = markAsFailedCheckbox.checked;
        const failureReason = failureReasonInput.value;

        if (markFailed && !failureReason) {
            alert('Please provide a failure reason.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch('/manufacturer/production-lines/update-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    item_name: itemName,
                    new_stage: newStage,
                    mark_as_failed: markFailed,
                    failure_reason: failureReason
                })
            });

            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                location.reload(); // Reload to reflect changes
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('An error occurred while updating.');
        }
    }
</script>
@endpush