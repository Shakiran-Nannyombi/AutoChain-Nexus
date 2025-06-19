<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RawMaterialChecklist;
use App\Models\Delivery;
use App\Models\Production;
use App\Models\Warehouse;
use App\Models\ProductionStage;
use App\Models\ProductionBatch;
use App\Models\Car;
use App\Models\WorkOrder;
use App\Models\User; // Added to potentially get active workers

class ManufacturerController extends Controller
{
    public function index()
    {
        $data = $this->getManufacturerDashboardData();
        $headerTitle = 'Manufacturing Dashboard';

        return view('pages.manufacturer-dashboard', array_merge($data, ['headerTitle' => $headerTitle]));
    }

    private function getManufacturerDashboardData()
    {
        // 1. Production Overview Metrics
        $totalWorkOrdersCompleted = WorkOrder::where('status', 'completed')->count();
        $totalProductionBatches = ProductionBatch::count();
        $dailyProduction = WorkOrder::where('status', 'completed')->whereDate('updated_at', today())->count();

        // Simulate Live Production Rate (e.g., completed units per hour in last 24 hours)
        $liveProductionRate = round($dailyProduction / 8, 1); // Assuming 8 working hours in a day

        // Overall Equipment Efficiency (OEE) - simplified for demo
        // Availability: percentage of time machines are running
        // Performance: actual output vs. ideal output
        // Quality: good units vs. total units
        // For now, let's use a fixed value or derive from a simple calculation.
        $oee = 85.0; // World Class is >85%

        // Downtime Today - Placeholder, ideally from a maintenance log
        $downtimeToday = rand(1, 4) . '.' . rand(0, 9); // Random hours.minutes

        // Quality Defects - Placeholder, ideally from quality control data
        $qualityDefects = rand(0, 2) . '.' . rand(0, 9);

        // 2. Production Lines - Real Time
        $productionLines = ProductionBatch::latest()->take(3)->get()->map(function ($batch) {
            $efficiency = $batch->efficiency ?? rand(70, 99); // Use actual efficiency or simulate
            $progress = $batch->progress ?? rand(1, 100); // Use actual progress or simulate

            return [
                'name' => 'LINE-' . strtoupper(substr($batch->car_name, 0, 1)) . ' - ' . $batch->model,
                'product' => $batch->car_name . ' ' . $batch->model,
                'target_hourly' => rand(20, 50),
                'hourly_progress' => rand(10, 45),
                'efficiency' => $efficiency,
                'status' => $batch->status, // Use actual status: in_progress, completed, on_hold
                'temperature' => rand(20, 45), // Simulated temperature
            ];
        })->toArray();

        // If not enough real production batches, add dummy ones
        if (count($productionLines) < 3) {
            $dummyLinesNeeded = 3 - count($productionLines);
            for ($i = 0; $i < $dummyLinesNeeded; $i++) {
                $productionLines[] = [
                    'name' => 'LINE-' . chr(65 + count($productionLines) + $i) . ' - ' . ['Sedan', 'SUV', 'Truck'][rand(0, 2)] . ' X' . rand(1, 3),
                    'product' => ['Sedan', 'SUV', 'Truck'][rand(0, 2)] . ' ' . ['X1', 'Z3', 'TS'][rand(0, 2)],
                    'target_hourly' => rand(20, 50),
                    'hourly_progress' => rand(10, 45),
                    'efficiency' => rand(70, 99),
                    'status' => ['running', 'maintenance', 'idle'][rand(0, 2)],
                    'temperature' => rand(20, 45),
                ];
            }
        }


        // 3. Production Alerts - Placeholder for now
        $productionAlerts = [
            [
                'type' => 'Emergency',
                'message' => 'LINE-C Maintenance Required',
                'description' => 'Temperature threshold exceeded',
                'status' => 'urgent',
            ],
            [
                'type' => 'Warning',
                'message' => 'Shift Change in 30 minutes',
                'description' => 'Prepare handover documentation',
                'status' => 'warning',
            ],
            [
                'type' => 'Info',
                'message' => 'Material Delivery Incoming',
                'description' => 'Steel shipment arriving at 2:00 PM',
                'status' => 'info',
            ],
        ];

        // 4. Quality Control Metrics
        // Using fixed values for demo, connect to ProductionStage or specific QualityControl model if available
        $qualityControlMetrics = [
            [
                'name' => 'Paint Quality',
                'current' => 98.5,
                'threshold' => 95,
                'status' => 'good', // good, excellent, warning, attention
            ],
            [
                'name' => 'Assembly Precision',
                'current' => 99.2,
                'threshold' => 98,
                'status' => 'excellent',
            ],
            [
                'name' => 'Safety Compliance',
                'current' => 94.8,
                'threshold' => 95,
                'status' => 'warning',
            ],
            [
                'name' => 'Durability Tests',
                'current' => 97.3,
                'threshold' => 96,
                'status' => 'good',
            ],
        ];

        // 5. Machine Health Monitoring
        $machineHealth = [
            [
                'name' => 'Welding Robot #1',
                'next_service' => '1 days',
                'health_score' => 92,
                'status' => 'healthy',
            ],
            [
                'name' => 'Paint Booth A',
                'next_service' => 'Today',
                'health_score' => 78,
                'status' => 'attention',
            ],
            [
                'name' => 'Assembly Line Motor',
                'next_service' => '7 days',
                'health_score' => 95,
                'status' => 'healthy',
            ],
            [
                'name' => 'Quality Scanner',
                'next_service' => '2 days',
                'health_score' => 88,
                'status' => 'good',
            ],
        ];

        // 6. Maintenance Schedule
        $maintenanceSchedule = [
            [
                'type' => 'Emergency Maintenance',
                'description' => 'Paint Booth A - Temperature sensor failure',
                'time_due' => 'Urgent',
                'status' => 'urgent',
            ],
            [
                'type' => 'Scheduled Maintenance',
                'description' => 'Quality Scanner - Routine calibration',
                'time_due' => '2 days',
                'status' => 'warning',
            ],
            [
                'type' => 'Preventive Maintenance',
                'description' => 'Welding Robot #1 - Oil change and inspection',
                'time_due' => '3 days',
                'status' => 'info',
            ],
        ];


        return compact(
            'liveProductionRate',
            'oee',
            'downtimeToday',
            'qualityDefects',
            'productionLines',
            'productionAlerts',
            'qualityControlMetrics',
            'machineHealth',
            'maintenanceSchedule'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:in_progress,completed,on_hold',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
        ]);

        WorkOrder::create($validated);

        return redirect()->route('manufacturing.index')->with('success', 'Work Order created successfully!');
    }

    public function createChecklist(Request $request)
    {
        $validated = $request->validate([
            'materials' => 'required|array',
            'materials.*.name' => 'required|string',
            'materials.*.required_quantity' => 'required|integer|min:1',
        ]);

        foreach ($validated['materials'] as $item) {
            RawMaterialChecklist::create([
                'manufacturer_id' => Auth::id(),
                'raw_material_name' => $item['name'],
                'required_quantity' => $item['required_quantity'],
                'delivered_quantity' => 0,
            ]);
        }

        return response()->json(['message' => 'Checklist created successfully']);
    }

    public function checklistStatus()
    {
        $checklist = RawMaterialChecklist::where('manufacturer_id', Auth::id())->get();
        return response()->json($checklist);
    }

    public function startProduction(Request $request)
    {
        $validated = $request->validate([
            'car_name' => 'required|string',
            'model' => 'required|string',
        ]);

        $checklist = RawMaterialChecklist::where('manufacturer_id', Auth::id())->get();

        foreach ($checklist as $item) {
            if ($item->delivered_quantity < $item->required_quantity) {
                return response()->json([
                    'error' => 'Cannot start production. Not all materials are delivered.'
                ], 400);
            }
        }

        // Create a new production batch
        $batch = ProductionBatch::create([
            'manufacturer_id' => Auth::id(),
            'car_name' => $validated['car_name'],
            'model' => $validated['model'],
            'current_stage' => 'Frame',
            'is_completed' => false
        ]);

        // Start first production stage
        ProductionStage::create([
            'production_batch_id' => $batch->id,
            'stage_name' => 'Frame',
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Production batch started.',
            'data' => $batch
        ]);
    }

    public function advanceProductionStage($batchId)
    {
        $batch = ProductionBatch::where('id', $batchId)
            ->where('manufacturer_id', Auth::id())
            ->firstOrFail();

        $stages = ['Frame', 'Engine', 'Interior', 'Paint', 'Quality Check', 'Completed'];
        $currentIndex = array_search($batch->current_stage, $stages);

        if ($currentIndex === false || $currentIndex >= count($stages) - 1) {
            return response()->json(['message' => 'Production already completed.']);
        }

        // Close previous stage
        $currentStage = ProductionStage::where('production_batch_id', $batch->id)
            ->where('stage_name', $batch->current_stage)
            ->latest()
            ->first();

        if ($currentStage) {
            $currentStage->ended_at = now();
            $currentStage->save();
        }

        // Advance to next stage
        $nextStage = $stages[$currentIndex + 1];
        $batch->current_stage = $nextStage;
        if ($nextStage === 'Completed') {
            $batch->is_completed = true;
        }
        $batch->save();

        // Create next stage entry
        ProductionStage::create([
            'production_batch_id' => $batch->id,
            'stage_name' => $nextStage,
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Production advanced.',
            'current_stage' => $nextStage
        ]);
    }

    public function sendToWarehouse(Request $request, $batchId)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id'
        ]);

        $batch = ProductionBatch::where('id', $batchId)
            ->where('manufacturer_id', Auth::id())
            ->where('is_completed', true)
            ->firstOrFail();

        $warehouse = Warehouse::find($validated['warehouse_id']);

        if ($warehouse->capacity <= $warehouse->cars()->count()) {
            return response()->json(['error' => 'Warehouse is full.'], 400);
        }

        // Send car to warehouse
        Car::create([
            'name' => $batch->car_name,
            'model' => $batch->model,
            'warehouse_id' => $warehouse->id,
            'manufacturer_id' => Auth::id()
        ]);

        return response()->json(['message' => 'Car sent to warehouse.']);
    }
}
