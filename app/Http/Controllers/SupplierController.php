<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierStock;
use App\Models\ChecklistRequest;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function stockManagement()
    {
        $stocks = SupplierStock::where('supplier_id', Auth::id())->get();
        
        // Fetch notifications for the current user
        $user = Auth::user();
        $unreadNotifications = ($user && is_object($user) && method_exists($user, 'unreadNotifications')) ? $user->unreadNotifications()->take(5)->get() : collect();
        $allNotifications = ($user && is_object($user) && method_exists($user, 'notifications')) ? $user->notifications()->take(10)->get() : collect();
        
        return view('dashboards.supplier.stock-management', compact('stocks', 'unreadNotifications', 'allNotifications'));
    }

    public function addStock(Request $request)
    {
        $request->validate([
        'material_name' => 'required|string',
        'quantity' => 'required|integer|min:1',
        'colour' => 'nullable|string'
    ]);

    $stock = SupplierStock::where('supplier_id', Auth::id())
        ->where('material_name', $request->material_name)
        ->first();

    if ($stock) {
        $stock->quantity += $request->quantity;
        $stock->colour = $request->colour; // update colour if changed
        $stock->save();
    } else {
        SupplierStock::create([
            'supplier_id' => Auth::id(),
            'material_name' => $request->material_name,
            'quantity' => $request->quantity,
            'colour' => $request->colour,
        ]);
    }

        return back()->with('success', 'Stock updated.');
    }

    public function checklistReceipt()
    {
        $checklists = ChecklistRequest::where('supplier_id', Auth::id())->get();
        return view('dashboards.supplier.checklist-receipt', compact('checklists'));
    }

    public function fulfillChecklist(Request $request, $id)
    {
        $checklist = ChecklistRequest::findOrFail($id);
        $materials = $checklist->materials_requested;

        foreach ($materials as $material => $amount) {
            $stock = SupplierStock::where('supplier_id', Auth::id())
                ->where('material_name', $material)->first();

            if ($stock && $stock->quantity >= $amount) {
                $stock->decrement('quantity', $amount);
            } else {
                return back()->with('error', "Not enough $material stock to fulfill.");
            }
        }

        Delivery::create([
            'supplier_id' => Auth::id(),
            'manufacturer_id' => $checklist->manufacturer_id,
            'materials_delivered' => $materials,
        ]);

        $checklist->update(['status' => 'fulfilled']);

        return back()->with('success', 'Checklist fulfilled and delivery recorded.');
    }

    public function deliveryHistory()
    {
        $deliveries = Delivery::where('supplier_id', Auth::id())->get();
        return view('dashboards.supplier.delivery-history', compact('deliveries'));
    }
}
