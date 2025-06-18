<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $totalItems = InventoryItem::sum('current_stock');

        $lowStockItems = InventoryItem::whereColumn('current_stock', '<=', 'min_stock_threshold')
                                        ->count();

        $criticalItems = InventoryItem::whereColumn('current_stock', '<=', 'critical_stock_threshold')
                                        ->count();

        $totalValue = InventoryItem::select(DB::raw('SUM(current_stock * unit_price) as total_value'))
                                    ->value('total_value');

        // Format totalValue to a मिलियन format if it's large
        if ($totalValue >= 1000000) {
            $totalValue = '$' . round($totalValue / 1000000, 1) . 'M';
        } else {
            $totalValue = '$' . number_format($totalValue, 2);
        }

        $inventoryItems = InventoryItem::orderBy('name')->get();

        return view('pages.inventory', compact(
            'totalItems',
            'lowStockItems',
            'criticalItems',
            'totalValue',
            'inventoryItems'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'current_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gte:current_stock',
            'min_stock_threshold' => 'required|integer|min:0|lt:current_stock',
            'critical_stock_threshold' => 'required|integer|min:0|lt:min_stock_threshold',
            'unit_price' => 'required|numeric|min:0',
        ]);

        InventoryItem::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Item added successfully!');
    }
}
