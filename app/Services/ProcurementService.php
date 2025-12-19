<?php

namespace App\Services;

use App\Models\ChecklistRequest;
use App\Models\SupplierOrder;
use App\Models\VendorOrder;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProcurementService
{
    /**
     * Get data for the checklists page.
     *
     * @param int $manufacturerId
     * @return array
     */
    public function getChecklistsData(int $manufacturerId): array
    {
        $suppliers = User::where('role', 'supplier')->where('status', 'approved')->get();
        $sentChecklists = ChecklistRequest::where('manufacturer_id', $manufacturerId)
            ->with('supplier')
            ->latest()
            ->get();
            
        return compact('suppliers', 'sentChecklists');
    }

    /**
     * Create a new checklist request.
     *
     * @param int $manufacturerId
     * @param array $data
     * @return void
     */
    public function createChecklist(int $manufacturerId, array $data): void
    {
        $materials = $data['materials'];
        $quantities = $data['quantities'];
        $supplierId = $data['supplier_id'];

        $materialsRequested = [];
        foreach ($materials as $i => $mat) {
            $mat = trim($mat);
            $qty = isset($quantities[$i]) ? (int)$quantities[$i] : 0;
            if ($mat && $qty > 0) {
                $materialsRequested[$mat] = $qty;
            }
        }

        ChecklistRequest::create([
            'manufacturer_id' => $manufacturerId,
            'supplier_id' => $supplierId,
            'materials_requested' => $materialsRequested,
            'status' => 'pending',
        ]);
    }

    /**
     * Get data for the orders page.
     *
     * @param int $manufacturerId
     * @param string|null $vendorStatus
     * @return array
     */
    public function getOrdersData(int $manufacturerId, ?string $vendorStatus = null): array
    {
        // Supplier orders
        $supplierOrders = SupplierOrder::with(['supplier', 'items'])
            ->orderByDesc('created_at')
            ->get();

        // Vendor orders
        $vendorOrdersQuery = VendorOrder::where('manufacturer_id', $manufacturerId);
        if ($vendorStatus && in_array($vendorStatus, ['fulfilled', 'cancelled'])) {
            $vendorOrdersQuery->where('status', $vendorStatus);
        }
        $vendorOrders = $vendorOrdersQuery->orderByDesc('created_at')->get();
        
        // Deliveries
        $deliveries = Delivery::where('manufacturer_id', $manufacturerId)
            ->with('supplier')
            ->orderByDesc('created_at')
            ->get();
        
        $productPrices = DB::table('products')->pluck('price', 'name');

        return compact('supplierOrders', 'vendorOrders', 'deliveries', 'productPrices');
    }

    /**
     * Remake an existing order.
     *
     * @param int $orderId
     * @return void
     */
    public function remakeOrder(int $orderId): void
    {
        $order = ChecklistRequest::findOrFail($orderId);
        $materials = $order->materials_requested;
        
        // If it's a string, decode it
        if (is_string($materials)) {
            $materials = json_decode($materials, true);
        }
        
        ChecklistRequest::create([
            'manufacturer_id' => $order->manufacturer_id,
            'supplier_id' => $order->supplier_id,
            'materials_requested' => $materials,
            'status' => 'pending',
        ]);
    }
}
