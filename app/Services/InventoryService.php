<?php

namespace App\Services;

use App\Models\SupplierStock;
use App\Models\RetailerStock;
use App\Models\Delivery;
use App\Models\ChecklistRequest;

class InventoryService
{
    /**
     * Get inventory status data including supplier and retailer stocks.
     *
     * @return array
     */
    public function getInventoryStatus(): array
    {
        $supplierStocks = SupplierStock::all();
        $retailerStocks = RetailerStock::all();

        $totalSupplierStock = $supplierStocks->sum('quantity');
        $totalRetailerStock = $retailerStocks->sum('quantity_received');

        return compact(
            'supplierStocks',
            'retailerStocks',
            'totalSupplierStock',
            'totalRetailerStock'
        );
    }

    /**
     * Get material receipt data including deliveries and confirmed orders.
     *
     * @param int $manufacturerId
     * @return array
     */
    public function getMaterialReceipts(int $manufacturerId): array
    {
        $deliveries = Delivery::where('manufacturer_id', $manufacturerId)
            ->with('supplier')
            ->latest()
            ->get();

        $confirmedOrders = ChecklistRequest::where('manufacturer_id', $manufacturerId)
            ->where('status', 'fulfilled')
            ->with('supplier')
            ->latest()
            ->get();

        return compact('deliveries', 'confirmedOrders');
    }
}
