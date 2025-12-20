<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ManufacturerDashboardService
{
    /**
     * Get aggregated metrics for the manufacturer dashboard.
     *
     * @param int $manufacturerId
     * @return array
     */
    public function getDashboardMetrics(int $manufacturerId): array
    {
        // Active Orders: count of fulfilled vendor_orders for this manufacturer
        $activeOrders = DB::table('vendor_orders')
            ->where('manufacturer_id', $manufacturerId)
            ->where('status', 'fulfilled')
            ->count();

        // Total Revenue: sum of total_amount for all vendor_orders for this manufacturer
        // Note: Using the variable name from controller, though logic implies separate calc.
        // The controller had a static value overriding this, but we will preserve the logic structure.
        $calculatedTotalRevenue = DB::table('vendor_orders')
            ->where('manufacturer_id', $manufacturerId)
            ->sum('total_amount');

        // Inventory: count of products for this manufacturer
        $inventoryCount = DB::table('products')
            ->where('manufacturer_id', $manufacturerId)
            ->count();

        // Active Vendors: count of unique vendor_ids in fulfilled vendor_orders for this manufacturer
        $activeVendors = DB::table('vendor_orders')
            ->where('manufacturer_id', $manufacturerId)
            ->where('status', 'fulfilled')
            ->distinct('vendor_id')
            ->count('vendor_id');

        // Recent Orders: last 5 fulfilled vendor_orders for this manufacturer
        $recentOrders = DB::table('vendor_orders')
            ->where('manufacturer_id', $manufacturerId)
            ->where('status', 'fulfilled')
            ->orderByDesc('ordered_at')
            ->limit(5)
            ->get();

        // Low Stock Products: products for this manufacturer with stock < 5
        $lowStockProducts = DB::table('products')
            ->where('manufacturer_id', $manufacturerId)
            ->where('stock', '<', 5)
            ->get();

        // Demo revenue values for dashboard display (Preserved from Controller)
        $monthlyRevenue = 12500000.00;
        $totalRevenue = 98765432.10;

        return compact(
            'activeOrders',
            'monthlyRevenue',
            'inventoryCount',
            'activeVendors',
            'recentOrders',
            'lowStockProducts',
            'totalRevenue'
        );
    }
}
