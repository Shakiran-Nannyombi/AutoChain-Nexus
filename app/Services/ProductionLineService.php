<?php

namespace App\Services;

use App\Repositories\Contracts\ProcessFlowRepositoryInterface;

class ProductionLineService
{
    protected ProcessFlowRepositoryInterface $repository;

    public function __construct(ProcessFlowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Calculate production lines status and return data for the view.
     *
     * @return array
     */
    public function getProductionLinesData(): array
    {
        $processFlows = $this->repository->getAll();

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

            if ($flow->isCompleted()) {
                $productionLines[$itemName]['completed_items']++;
            } else {
                $productionLines[$itemName]['has_incomplete_item'] = true;
            }
            if ($flow->isFailed()) {
                $productionLines[$itemName]['has_failed_item'] = true;
            }
        }

        // Determine the overall status and current stage for each line
        foreach ($productionLines as $itemName => &$data) {
            // Calculate max_stage_progress for the line
            $currentLineItems = $processFlows->where('item_name', $itemName);
            foreach ($currentLineItems as $item) {
                // Use model methods if available or fallback to direct check
                $stage = $item->isCompleted() ? 'completed' : ($item->isFailed() ? 'failed' : $item->current_stage);
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

        return compact('productionLines', 'processFlows');
    }
}
